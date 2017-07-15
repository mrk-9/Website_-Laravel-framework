<?php namespace App\Http\Controllers\Main\Api;

use App\Acquisition;
use App\AdPlacement;
use App\Auction;
use App\Booking;
use App\Events\AuctionWasDone;
use App\Events\BookingWasDone;
use App\Events\OfferWasDone;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Main\SaveBuyerRequest;
use App\Invoice;
use App\Offer;
use App\TechnicalSupport;
use App\Template;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\OfferWasCanceled;

class BuyController extends Controller
{

    const PAYMENT_TYPE_CARD = 'credit_card';
    const PAYMENT_TYPE_TRANSFER = 'transfer';

    public function postBuyAdPlacement(AdPlacement $adPlacement, Request $request)
    {
        $buyType = $request->get('buyType');
        $buyPrice = $request->get('price');
        $templateId = $request->get('template');
        $technicalSupportId = $request->get('technicalSupport')['id'];
        $paymentType = $request->get('paymentType');
        $stripeToken = $request->get('stripeToken');
        $stripeSaveCard = $request->get('stripeSaveCard');
        $orderValidation = $request->get('orderValidation');
        $cgvValidation = $request->get('cgvValidation');
        $brief = $request->get('brief');

        $user = Auth::user()->get();

        if ($orderValidation !== true) {
            self::jsonException("orderValidation", "Il faut valider le bon de commande pour passer commande", 422);
        }

        if ($cgvValidation !== true && $technicalSupportId !== null) {
            self::jsonException("cgvValidation", "Il faut valider les CGV", 422);
        }

        if ($buyType === null) {
            self::jsonException("buyType", "Il faut un type d'achat pour passer commande", 422);
        }

        if ($buyPrice === null && ($buyType == AdPlacement::TYPE_OFFER || $buyType == AdPlacement::TYPE_AUCTION)) {
            self::jsonException("price", "Il un prix pour cet type d'achat", 422);
        }

        if ($adPlacement->finished) {
            self::jsonException("ad_placement", "ad_placement_bought", 422);
        }

        if ($adPlacement->locked) {
            self::jsonException("ad_placement", "ad_placement_locked", 422);
        }

        $buyObject = [
            'user_id' => $user->id,
            'ad_placement_id' => $adPlacement->id,
            'order' => $orderValidation
        ];

        if ($buyType == AdPlacement::TYPE_AUCTION && $adPlacement->type == AdPlacement::TYPE_AUCTION) {
            if ($buyPrice >= $adPlacement->user_min_price) {
                $last_buyer = $adPlacement->last_auction;
                $this->createAcquisition($user, $buyType, $buyPrice, $adPlacement, $stripeToken, $stripeSaveCard, $paymentType, $technicalSupportId, $templateId, $brief);
                $buyObject["amount"] = $buyPrice;
                $auction = Auction::create($buyObject);
                $adPlacement->price = $buyPrice;
                $adPlacement->save();

                if (!is_null($last_buyer)) {
                    $last_buyer = $last_buyer->user;
                    event(new AuctionWasDone($auction, $last_buyer));
                }

                return response()->json("Enchère validée");
            } else {
                self::jsonException("price", "price_too_low", 422);
            }
        } elseif ($buyType == AdPlacement::TYPE_BOOKING && ($adPlacement->type == AdPlacement::TYPE_BOOKING || $adPlacement->type == AdPlacement::TYPE_HYBRID)) {
            $acquisition = $this->createAcquisition($user, $buyType, $adPlacement->price, $adPlacement, $stripeToken, $stripeSaveCard, $paymentType, $technicalSupportId, $templateId, $brief);
            $booking = Booking::create($buyObject);
            if ($paymentType == self::PAYMENT_TYPE_TRANSFER) {
                $adPlacement->lock($booking);
            } else {
                $invoice = Invoice::createFromAcquisition($acquisition);
                $acquisition->invoice_id = $invoice->id;
                $acquisition->save();
            }

            event(new BookingWasDone($booking));

            return response()->json("Achat validé");
        } elseif ($buyType == AdPlacement::TYPE_OFFER && ($adPlacement->type == AdPlacement::TYPE_OFFER || $adPlacement->type == AdPlacement::TYPE_HYBRID)) {
            if($adPlacement->getUserOfferAttribute() == null) {
                if ($buyPrice >= $adPlacement->user_min_price && $buyPrice <= $adPlacement->user_max_price) {
                    $this->createAcquisition($user, $buyType, $buyPrice, $adPlacement, $stripeToken, $stripeSaveCard, $paymentType, $technicalSupportId, $templateId, $brief);

                    $buyObject["amount"] = $buyPrice;
                    $offer = Offer::create($buyObject);

                    event(new OfferWasDone($offer));

                    return response()->json("Offre validée");
                } else {
                    self::jsonException("price", "price_not_valid", 422);
                }
            } else {
                self::jsonException("ad_placement", "Vous avez déjà une offre en cours, il faut la supprimer si vous souhaitez en faire une nouvelle", 422);
            }

        }
        self::jsonException("buyType", "Le type n'est pas valide par rapport à l'annonce", 422);
    }

    private function stripePayment($user, $buyType, $price, $adPlacement, $stripeToken, $stripeSaveCard)
    {
        $capture = false;
        if ($buyType == AdPlacement::TYPE_BOOKING) {
            $capture = true;
        }
        if (isset($stripeToken) && $stripeSaveCard == "on") {
            try {
                $user->buyer->saveCreditCard($stripeToken);
            } catch (\Stripe\Error\InvalidRequest $e) {
                self::jsonException("stripe", "stripe_token_invalid", 422);
            }
        }
        $chargeInfos = array(
            "amount" => intval($price * 100),
            "currency" => "eur",
            "capture" => $capture,
            "description" => $buyType . " by buyer n°" . $user->buyer->id . " for AdPlacement n°" . $adPlacement->id
        );
        if (is_null($user->buyer->getCreditCardAttribute())) {
            if (isset($stripeToken)) {
                try {
                    $chargeInfos["source"] = $stripeToken;
                    $charge = \Stripe\Charge::create($chargeInfos);
                } catch (\Stripe\Error\InvalidRequest $e) {
                    self::jsonException("stripe", "stripe_token_invalid", 422);
                }
            } else {
                self::jsonException("stripe", "stripe_no_card", 422);
            }
        } else {
            try {
                $chargeInfos["customer"] = $user->buyer->stripe_id;
                $charge = \Stripe\Charge::create($chargeInfos);
            } catch (\Stripe\Error\Card $e) {
                self::jsonException("stripe", "stripe_error", 422);
            }
            if ($stripeSaveCard != "on") {
                $user->buyer->saveCreditCard(null);
            }
        }
        return $charge;
    }

    private function createAcquisition($user, $buyType, $buyPrice, $adPlacement, $stripeToken, $stripeSaveCard, $paymentType, $technicalSupportId, $templateId, $brief)
    {
        if ($paymentType == self::PAYMENT_TYPE_CARD || $paymentType == self::PAYMENT_TYPE_TRANSFER) {
            $acquisition = new Acquisition();
            $acquisition->user_id = $user->id;
            $acquisition->ad_placement_id = $adPlacement->id;
            $acquisition->price = $adPlacement->depositPrice($buyPrice);
            $acquisition->setVatRate();
            if (isset($technicalSupportId)) {
                $technicalSupport = TechnicalSupport::find(intval($technicalSupportId));
                if (isset($technicalSupport)) {
                    if ($technicalSupport->id === 1) {
                        if (isset($templateId)) {
                            $template = Template::find(intval($templateId));
                            if (isset($template)) {
                                $acquisition->template_id = $template->id;
                                $acquisition->brief = $brief;
                            } else {
                                self::jsonException("template", "L'id du template n'est pas valide", 422);
                            }
                        } else {
                            self::jsonException("template", "Un id de template est obligatoire", 422);
                        }
                    }
                    $acquisition->technical_support_id = $technicalSupport->id;
                    $acquisition->technical_support_price = $technicalSupport->price;

                } else {
                    self::jsonException("technicalSupport", "L'id du support technique n'est pas valide", 422);
                }
            }
            if ($paymentType == self::PAYMENT_TYPE_CARD) {
                $charge = $this->stripePayment($user, $buyType, $acquisition->getTotalAttribute(), $adPlacement, $stripeToken, $stripeSaveCard);
                $acquisition->charge_id = $charge->id;
                if ($charge->captured) {
                    $acquisition->charge_status = Acquisition::CHARGE_SUCCESS;
                } else {
                    $acquisition->charge_status = Acquisition::CHARGE_PENDING;
                }
            } else {
                $acquisition->transfer_status = Acquisition::TRANSFER_PENDING;
            }
            $this->cleanUserAcquisition($user, $adPlacement);
            $acquisition->save();
            return $acquisition;
        } else {
            self::jsonException("paymentType", "Le type de paiement n'est pas valide", 422);
        }
    }

    private function cleanUserAcquisition(User $user, AdPlacement $adPlacement)
    {
        Auction::where('auction.user_id', $user->id)->where('auction.ad_placement_id', $adPlacement->id)->delete();
        Offer::where('offer.user_id', $user->id)->where('offer.ad_placement_id', $adPlacement->id)->delete();
        Booking::where('booking.user_id', $user->id)->where('booking.ad_placement_id', $adPlacement->id)->delete();
        Acquisition::where('acquisition.user_id', $user->id)->where('acquisition.ad_placement_id', $adPlacement->id)->delete();
    }

    public function deleteOfferAdPlacement(AdPlacement $adPlacement)
    {
        if ($adPlacement->type == AdPlacement::TYPE_OFFER || $adPlacement->type == AdPlacement::TYPE_HYBRID) {
            $user = Auth::user()->get();
            if ($adPlacement->getUserOfferAttribute($user) != null) {
                $this->cleanUserAcquisition($user, $adPlacement);
                event(new OfferWasCanceled($user, $adPlacement));
                return response()->json("Offre supprimée avec succès");
            }
            self::jsonException("ad_placement", "Vous n'avez pas d'offre pour cet emplacement", 422);
        }
        self::jsonException("ad_placement", "L'emplacement doit être de type hybride ou offre", 422);
    }

    public static function jsonException($error, $message, $code = 200)
    {
        throw new HttpResponseException(response()->json([$error => $message], $code));
    }

}

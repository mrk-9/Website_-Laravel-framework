<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;

class Buyer extends BaseModel implements SluggableInterface
{

    use SoftDeletes, SluggableTrait;

    const TYPE_AGENCY = 'agency';
    const TYPE_ADVERTISER = 'advertiser';

    const STATUS_PENDING = 'pending';
    const STATUS_VALID = 'valid';

    protected $table = 'buyer';

    protected $stripeCustomer = null;

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug',
    ];


    protected $fillable = [
        'name',
        'company_type',
        'address',
        'zipcode',
        'city',
        'phone',
        'email',
        'status',
        'activity',
        'type',
        'user_id',
        'customers'
    ];

    protected static $search_rules = [
        'name' => [
            'operator' => 'LIKE',
            'value' => '%{value}%'
        ]
    ];

    protected static function boot()
    {
        parent::boot();

        self::created(function (Buyer $buyer) {
            $buyer->createStripeCustomer();
        });
    }

    public function referent()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function getTypeFrAttribute()
    {
        switch ($this->type) {
            case self::TYPE_AGENCY:
                return "agence";
            case self::TYPE_ADVERTISER:
                return "annonceur";
        }
    }

    private function createStripeCustomer()
    {
        $customer = \Stripe\Customer::create(array(
            "description" => "Buyer N°" . $this->id . " : " . $this->name,
        ));

        $this->stripe_id = $customer->id;
        $this->save();
    }

    private function getStripeCustomer()
    {
        if (is_null($this->stripeCustomer)) {
            if (is_null($this->stripe_id)) {
                $this->createStripeCustomer();
            }

            $this->stripeCustomer = \Stripe\Customer::retrieve($this->stripe_id);
        }

        return $this->stripeCustomer;
    }

    public function getCreditCardAttribute()
    {
        $default_source = $this->getStripeCustomer()->default_source;

        if (is_null($default_source)) {
            return null;
        }

        return $this->getStripeCustomer()->sources->retrieve($default_source);
    }

    public function saveCreditCard($stripe_token)
    {
        $default_source = $this->getStripeCustomer()->default_source;

        if (!is_null($default_source) || is_null($stripe_token)) {
            $this->getStripeCustomer()->sources->retrieve($default_source)->delete();
        }

        if (!is_null($stripe_token)) {
            $this->getStripeCustomer()->source = $stripe_token;
            $this->getStripeCustomer()->save();
        }
    }
}

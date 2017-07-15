<?php namespace App\Extensions\TwigBridge;

use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Access Laravels auth class in your Twig templates.
 */
class PublicEnv extends Twig_Extension
{

    private $publicEnv = ['STRIPE_PUBLIC_KEY', 'DEPOSIT_PERCENT', 'VAT_RATE', 'FACEBOOK', 'TWITTER', 'GOOGLE_PLUS', 'MAIL_PUBLIC'];

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'App_Extensions_Twigbridge_PublicEnv';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('public_env', function ($name) {
                foreach ($this->publicEnv as $var) {
                    if ($var == $name) {
                        return env($name);
                    }
                }
            })
        ];
    }
}

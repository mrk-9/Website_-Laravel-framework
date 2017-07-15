<?php namespace App\Extensions\TwigBridge;

use Auth;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Access Laravels auth class in your Twig templates.
 */
class AdNetworkAuth extends Twig_Extension
{
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    protected $auth;

    /**
     * Create a new auth extension.
     *
     * @param \Illuminate\Auth\AuthManager
     */
    public function __construct()
    {
        $this->auth = Auth::ad_network();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'App_Extensions_Twigbridge_AdNetworkUserAuth';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('ad_network_auth_check', [$this->auth, 'check']),
            new Twig_SimpleFunction('ad_network_auth_guest', [$this->auth, 'guest']),
            new Twig_SimpleFunction('ad_network_auth_get', [$this->auth, 'get']),
        ];
    }
}

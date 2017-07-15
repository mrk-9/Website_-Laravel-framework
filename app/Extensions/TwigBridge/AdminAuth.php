<?php namespace App\Extensions\TwigBridge;

use Auth;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Access Laravels auth class in your Twig templates.
 */
class AdminAuth extends Twig_Extension
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
        $this->auth = Auth::admin();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'App_Extensions_Twigbridge_AdminAuth';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('admin_auth_check', [$this->auth, 'check']),
            new Twig_SimpleFunction('admin_auth_guest', [$this->auth, 'guest']),
            new Twig_SimpleFunction('admin_auth_get', [$this->auth, 'get']),
        ];
    }
}

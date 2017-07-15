<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		'App\Http\Middleware\VerifyCsrfToken',
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'admin.auth' => 'App\Http\Middleware\Admin\Authenticate',
		'admin.guest' => 'App\Http\Middleware\Admin\RedirectIfAuthenticated',
		'user.auth' => 'App\Http\Middleware\Main\Authenticate',
		'user.guest' => 'App\Http\Middleware\Main\RedirectIfAuthenticated',
		'ad_network.auth' => 'App\Http\Middleware\AdNetwork\Authenticate',
		'ad_network.guest' => 'App\Http\Middleware\AdNetwork\RedirectIfAuthenticated',
		'ad_placement.authorized' => 'App\Http\Middleware\AdNetwork\AdPlacementAuthorized',
		'media.authorized' => 'App\Http\Middleware\AdNetwork\MediaAuthorized',
	];

}

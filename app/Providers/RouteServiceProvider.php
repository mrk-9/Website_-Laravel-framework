<?php namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use App\AdPlacement;
use App\Media;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		parent::boot($router);

		$router->bind('ad_placement', function($value) {
			return AdPlacement::findBySlugOrIdOrFail($value);
		});

		$router->bind('emplacement', function($value) {
			return AdPlacement::findBySlugOrIdOrFail($value);
		});

		$router->bind('media', function($value) {
			return Media::findBySlugOrIdOrFail($value);
		});

		$router->model('admin', 'App\Admin');
		$router->model('buyer', 'App\Buyer');
		$router->model('user', 'App\User');
		$router->model('booking', 'App\Booking');
		$router->model('auction', 'App\Auction');
		$router->model('offer', 'App\Offer');
		$router->model('acquisition', 'App\Acquisition');
		$router->model('selection', 'App\Selection');
		$router->bind('support', function($value) {
			return \App\Support::findBySlugOrId($value);
		});
		$router->model('category', 'App\Category');
		$router->model('target', 'App\Target');
		$router->model('theme', 'App\Theme');
		$router->model('broadcasting_area', 'App\BroadcastingArea');
		$router->model('template', 'App\Template');
		$router->model('technical_support', 'App\TechnicalSupport');
		$router->model('ad_network', 'App\AdNetwork');
		$router->model('ad_network_user', 'App\AdNetworkUser');
		$router->bind('format', function($value) {
			return \App\Format::findBySlugOrId($value);
		});
		$router->model('invoice', 'App\Invoice');
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		/**
		 * Admin
		 */
		$router->group([
			'namespace' => $this->namespace . '\Admin',
			'domain' => 'admin.' . env('APP_DOMAIN')
		], function($router)
		{
			require app_path('Http/routes/admin.php');
		});

		/**
		 * AdNetwork
		 */
		$router->group([
			'namespace' => $this->namespace . '\AdNetwork',
			'domain' => 'regie.' . env('APP_DOMAIN')
		], function($router)
		{
			require app_path('Http/routes/ad_network.php');
		});

		/**
		 * Main
		 */
		$router->group([
			'namespace' => $this->namespace . '\Main',
			'domain' => 'www.' . env('APP_DOMAIN')
		], function($router)
		{
			require app_path('Http/routes/main.php');
		});
	}

}

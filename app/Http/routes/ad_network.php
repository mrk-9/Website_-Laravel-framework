<?php

/**
 * ==============================
 * 		AdNetworkUser Routes
 * ==============================
 */

Route::group(['prefix' => 'authentification'], function()
{
	Route::get('inscription', [
		'as' => 'ad-network.auth.register',
		'uses' => 'Auth\AuthController@getRegister'
	]);

	Route::post('register', [
		'as' => 'ad-network.auth.register.post',
		'uses' => 'Auth\AuthController@postRegister'
	]);

	Route::get('connexion', [
		'as' => 'ad-network.auth.login',
		'uses' => 'Auth\AuthController@getLogin'
	]);

	Route::post('login', [
		'as' => 'ad-network.auth.login.post',
		'uses' => 'Auth\AuthController@postLogin'
	]);

	Route::get('logout', [
		'as' => 'ad-network.auth.logout',
		'uses' => 'Auth\AuthController@getLogout'
	]);
});

/**
 * Password reset
 */
Route::group(['prefix' => 'password'], function()
{
	Route::post('reset/email', [
		'uses' => 'Auth\PasswordController@postResetEmail',
		'as' => 'ad-network.password.email.reset',
	]);

	Route::post('reset', [
		'uses' => 'Auth\PasswordController@postReset',
		'as' => 'ad-network.password.reset',
	]);

	Route::get('reset', [
		'uses' => 'Auth\PasswordController@getReset',
		'as' => 'ad-network.password.form.reset',
	]);
});

Route::group(['middleware' => 'ad_network.auth'], function() {

	Route::group(['prefix' => 'api'], function()
	{
		Route::get('category', 'Api\CategoryController@index');
		Route::get('support', 'Api\SupportController@index');
		Route::get('support-type', 'Api\SupportTypeController@index');
		Route::get('ad-network', 'Api\AdNetworkController@index');
		Route::get('broadcasting-area', 'Api\BroadcastingAreaController@index');
		Route::get('target', 'Api\TargetController@index');
		Route::get('theme', 'Api\ThemeController@index');
		Route::get('media', 'Api\MediaController@index');
		Route::get('format', 'Api\FormatController@index');
	});

	Route::get('', [
			'uses' => 'HomeController@index',
			'as' => 'ad-network.home',
	]);

	/**
	 * Alias for resource route
	 * named ad-network.ad-network-user.index
	 */
	Route::get('utilisateurs', [
		'uses' => 'AdNetworkUserController@index',
		'as' => 'ad-network.ad-network-user.list',
	]);

	Route::resource('ad-network-user', 'AdNetworkUserController', [
		'only' => ['index', 'destroy', 'update', 'store'],
		'names' => [
			'index' => 'ad-network.ad-network-user.index',
			'store' => 'ad-network.ad-network-user.store',
			'update' => 'ad-network.ad-network-user.update',
			'destroy' => 'ad-network.ad-network-user.destroy'
		],
	]);

	Route::group(['middleware' => 'media.authorized'], function() {
		Route::resource('media', 'MediaController', [
			'only' => ['index', 'store', 'update', 'edit', 'destroy', 'show'],
			'names' => [
				'index' => 'ad-network.media.index',
				'store' => 'ad-network.media.store',
				'update' => 'ad-network.media.update',
				'edit' => 'ad-network.media.edit',
				'destroy' => 'ad-network.media.destroy',
				'show' => 'ad-network.media.show',
			],
		]);
	});

	Route::group(['middleware' => 'media.authorized', 'prefix' => '{media}'], function() {
		Route::post('cover', [
			'uses' => 'MediaController@addCover',
			'as' => 'ad-network.media.cover'
		]);
		Route::post('technical-doc', [
			'uses' => 'MediaController@addTechnicalDoc',
			'as' => 'ad-network.media.technical-doc'
		]);
	});

	Route::group(['middleware' => ['ad_placement.authorized', 'media.authorized']], function() {
		Route::resource('emplacement', 'AdPlacementController', [
			'only' => ['index', 'show', 'store', 'create', 'update', 'destroy'],
			'names' => [
				'index' => 'ad-network.ad-placement.index',
				'show' => 'ad-network.ad-placement.show',
				'store' => 'ad-network.ad-placement.store',
				'create' => 'ad-network.ad-placement.create',
				'update' => 'ad-network.ad-placement.update',
				'destroy' => 'ad-network.ad-placement.destroy'
			],
		]);

		Route::group(['prefix' => 'ad-placement'], function()
		{
			Route::post('{ad_placement}/winner/validate-publication', [
				'uses' => 'AdPlacementController@postValidatePublication',
				'as' => 'ad-network.ad-placement.validate.publication'
			]);
		});
	});


	/**
	 * Alias for resource route
	 * named ad-network.ad-placement-earned.index
	 */
	Route::get('offres-gagnantes', [
		'uses' => 'AdPlacementEarnedController@index',
		'as' => 'ad-network.ad-placement-earned.list'
	]);

	Route::group(['prefix' => 'ad-placement-earned'], function()
	{
		Route::get('', [
			'uses' => 'AdPlacementEarnedController@index',
			'as' => 'ad-network.ad-placement-earned.index'
		]);
	});

	Route::group(['prefix' => 'booking'], function()
	{
		Route::get('', [
			'uses' => 'BookingController@index',
			'as' => 'ad-network.booking.index',
		]);

		Route::post('{booking}/validate-publication', [
			'uses' => 'BookingController@postValidatePublication',
			'as' => 'ad-network.booking.validate.publication'
		]);
	});

	Route::group(['prefix' => 'auction'], function()
	{
		Route::get('', [
			'uses' => 'AuctionController@index',
			'as' => 'ad-network.auction.index',
		]);

		Route::post('{auction}/validate-publication', [
			'uses' => 'AuctionController@postValidatePublication',
			'as' => 'ad-network.auction.validate.publication'
		]);
	});

	Route::group(['prefix' => 'offer'], function()
	{
		Route::get('', [
			'uses' => 'OfferController@index',
			'as' => 'ad-network.offer.index',
		]);

		Route::post('{offer}/accept', [
			'uses' => 'OfferController@postAccept',
			'as' => 'ad-network.offer.accept'
		]);

		Route::post('{offer}/validate-publication', [
			'uses' => 'OfferController@postValidatePublication',
			'as' => 'ad-network.offer.validate.publication'
		]);
	});

	Route::group(['prefix' => 'mon-compte'], function()
	{
		Route::get('edition', [
			'uses' => 'AccountController@edit',
			'as' => 'ad-network.account.edit'
		]);

		Route::post('update', [
			'uses' => 'AccountController@update',
			'as' => 'ad-network.account.update'
		]);
	});
});

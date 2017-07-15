<?php

/**
 * ==============================
 * 			Admin Routes
 * ==============================
 */

Route::controller('auth', 'Auth\AuthController', [
	'getLogout' => 'admin.auth.logout',
	'postLogin' => 'admin.auth.login.post'
]);

Route::group(['middleware' => 'admin.auth'], function() {

	Route::group(['prefix' => 'api'], function()
	{
		Route::get('user', 'Api\UserController@index');
		Route::get('media/{media?}', 'Api\MediaController@index');
		Route::get('category', 'Api\CategoryController@index');
		Route::get('support', 'Api\SupportController@index');
		Route::get('broadcasting-area', 'Api\BroadcastingAreaController@index');
		Route::get('ad-network', 'Api\AdNetworkController@index');
		Route::get('ad-network-user', 'Api\AdNetworkUserController@index');
		Route::get('target', 'Api\TargetController@index');
		Route::get('theme', 'Api\ThemeController@index');
		Route::get('format', 'Api\FormatController@index');
	});

	Route::get('', [
			'uses' => 'HomeController@index',
			'as' => 'admin.home',
	]);

	/**
	 * Resources
	 */
	Route::resource('admin', 'AdminController', [
		'only' => ['index', 'store', 'update', 'destroy'],
		'names' => [
			'index' => 'admin.admin.index',
			'store' => 'admin.admin.store',
			'update' => 'admin.admin.update',
			'destroy' => 'admin.admin.destroy'
		],
	]);

	Route::resource('buyer', 'BuyerController', [
		'only' => ['index', 'store', 'update', 'destroy', 'show'],
		'names' => [
			'index' => 'admin.buyer.index',
			'store' => 'admin.buyer.store',
			'update' => 'admin.buyer.update',
			'destroy' => 'admin.buyer.destroy',
			'show' => 'admin.buyer.show'
		],
	]);

	Route::resource('user', 'UserController', [
		'only' => ['index', 'destroy', 'update'],
		'names' => [
			'index' => 'admin.user.index',
			'update' => 'admin.user.update',
			'destroy' => 'admin.user.destroy'
		],
	]);

	Route::group(['prefix' => 'booking'], function()
	{
		Route::get('', [
			'uses' => 'BookingController@index',
			'as' => 'admin.booking.index',
		]);

		Route::post('{booking}/destroy', [
			'uses' => 'BookingController@destroy',
			'as' => 'admin.booking.destroy'
		]);

		Route::get('{booking}', [
			'uses' => 'BookingController@show',
			'as' => 'admin.booking.show'
		]);

		Route::post('{booking}/validate-transfer', [
			'uses' => 'BookingController@postValidateTransfer',
			'as' => 'admin.booking.validate.transfer'
		]);

		Route::post('{booking}/validate-publication', [
			'uses' => 'BookingController@postValidatePublication',
			'as' => 'admin.booking.validate.publication'
		]);
	});

	Route::resource('auction', 'AuctionController', [
		'only' => ['index', 'destroy', 'show', 'update'],
		'names' => [
			'index' => 'admin.auction.index',
			'destroy' => 'admin.auction.destroy',
			'show' => 'admin.auction.show',
			'update' => 'admin.auction.update'
		],
	]);

	Route::group(['prefix' => 'auction'], function()
	{
		Route::get('', [
			'uses' => 'AuctionController@index',
			'as' => 'admin.auction.index',
		]);

		Route::post('{auction}/destroy', [
			'uses' => 'AuctionController@destroy',
			'as' => 'admin.auction.destroy'
		]);

		Route::get('{auction}', [
			'uses' => 'AuctionController@show',
			'as' => 'admin.auction.show'
		]);

		Route::post('{auction}/validate-transfer', [
			'uses' => 'AuctionController@postValidateTransfer',
			'as' => 'admin.auction.validate.transfer'
		]);

		Route::post('{auction}/validate-publication', [
			'uses' => 'AuctionController@postValidatePublication',
			'as' => 'admin.auction.validate.publication'
		]);
	});

	Route::group(['prefix' => 'offer'], function()
	{
		Route::get('', [
			'uses' => 'OfferController@index',
			'as' => 'admin.offer.index',
		]);

		Route::post('{offer}/destroy', [
			'uses' => 'OfferController@destroy',
			'as' => 'admin.offer.destroy'
		]);

		Route::get('{offer}', [
			'uses' => 'OfferController@show',
			'as' => 'admin.offer.show'
		]);

		Route::post('{offer}/accept', [
			'uses' => 'OfferController@postAccept',
			'as' => 'admin.offer.accept'
		]);

		Route::post('{offer}/validate-transfer', [
			'uses' => 'OfferController@postValidateTransfer',
			'as' => 'admin.offer.validate.transfer'
		]);

		Route::post('{offer}/validate-publication', [
			'uses' => 'OfferController@postValidatePublication',
			'as' => 'admin.offer.validate.publication'
		]);
	});

	Route::resource('emplacement', 'AdPlacementController', [
		'only' => ['index', 'show', 'store', 'update'],
		'names' => [
			'index' => 'admin.ad-placement.index',
			'show' => 'admin.ad-placement.show',
			'store' => 'admin.ad-placement.store',
			'update' => 'admin.ad-placement.update'
		],
	]);

	Route::resource('acquisition', 'AcquisitionController', [
		'only' => ['index', 'show'],
		'names' => [
			'index' => 'admin.acquisition.index',
			'show' => 'admin.acquisition.show',
		],
	]);

	Route::resource('selection', 'SelectionController', [
		'only' => ['index', 'show'],
		'names' => [
			'index' => 'admin.selection.index',
			'show' => 'admin.selection.show',
		],
	]);

	Route::resource('support', 'SupportController', [
		'only' => ['index', 'destroy', 'update', 'store'],
		'names' => [
			'index' => 'admin.support.index',
			'destroy' => 'admin.support.destroy',
			'update' => 'admin.support.update',
			'store' => 'admin.support.store',
		],
	]);

	Route::resource('category', 'CategoryController', [
		'only' => ['index', 'destroy', 'update', 'store'],
		'names' => [
			'index' => 'admin.category.index',
			'destroy' => 'admin.category.destroy',
			'update' => 'admin.category.update',
			'store' => 'admin.category.store',
		],
	]);

	Route::resource('target', 'TargetController', [
		'only' => ['index', 'store', 'destroy', 'update'],
		'names' => [
			'index' => 'admin.target.index',
			'store' => 'admin.target.store',
			'destroy' => 'admin.target.destroy',
			'update' => 'admin.target.update',
		],
	]);

	Route::resource('theme', 'ThemeController', [
		'only' => ['index', 'store', 'destroy', 'update'],
		'names' => [
			'index' => 'admin.theme.index',
			'store' => 'admin.theme.store',
			'destroy' => 'admin.theme.destroy',
			'update' => 'admin.theme.update',
		],
	]);

	Route::resource('broadcasting-area', 'BroadcastingAreaController', [
		'only' => ['index', 'store', 'destroy', 'update'],
		'names' => [
			'index' => 'admin.broadcasting-area.index',
			'store' => 'admin.broadcasting-area.store',
			'destroy' => 'admin.broadcasting-area.destroy',
			'update' => 'admin.broadcasting-area.update',
		]
	]);

	Route::resource('media', 'MediaController', [
		'only' => ['index', 'store', 'update', 'destroy'],
		'names' => [
			'index' => 'admin.media.index',
			'store' => 'admin.media.store',
			'update' => 'admin.media.update',
			'destroy' => 'admin.media.destroy',
		],
	]);

	Route::group(['prefix' => '{media}'], function() {
		Route::post('cover', [
			'uses' => 'MediaController@addCover',
			'as' => 'admin.media.cover'
		]);
		Route::post('technical-doc', [
			'uses' => 'MediaController@addTechnicalDoc',
			'as' => 'admin.media.technical-doc'
		]);
	});

	Route::resource('template', 'TemplateController', [
		'only' => ['index', 'store', 'update', 'destroy'],
		'names' => [
			'index' => 'admin.template.index',
			'store' => 'admin.template.store',
			'update' => 'admin.template.update',
			'destroy' => 'admin.template.destroy',
		],
	]);

	Route::resource('technical-support', 'TechnicalSupportController', [
		'only' => ['index', 'store', 'update', 'destroy'],
		'names' => [
			'index' => 'admin.technical-support.index',
			'store' => 'admin.technical-support.store',
			'update' => 'admin.technical-support.update',
			'destroy' => 'admin.technical-support.destroy',
		],
	]);

	Route::resource('ad-network', 'AdNetworkController', [
		'only' => ['index', 'update', 'store', 'destroy', 'show'],
		'names' => [
			'index' => 'admin.ad-network.index',
			'update' => 'admin.ad-network.update',
			'store' => 'admin.ad-network.store',
			'destroy' => 'admin.ad-network.destroy',
			'show' => 'admin.ad-network.show'
		],
	]);

	Route::resource('ad-network-user', 'AdNetworkUserController', [
		'only' => ['index', 'destroy', 'update', 'store'],
		'names' => [
			'index' => 'admin.ad-network-user.index',
			'store' => 'admin.ad-network-user.store',
			'update' => 'admin.ad-network-user.update',
			'destroy' => 'admin.ad-network-user.destroy'
		],
	]);

	Route::resource('format', 'FormatController', [
		'only' => ['index', 'destroy', 'update', 'store'],
		'names' => [
			'index' => 'admin.format.index',
			'store' => 'admin.format.store',
			'update' => 'admin.format.update',
			'destroy' => 'admin.format.destroy'
		],
	]);
});

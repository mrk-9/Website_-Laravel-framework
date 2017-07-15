<?php

Route::get('', [
	'as' => 'main.home',
	'uses' => 'HomeController@getIndex',
]);

// temporary

// Route::group(['prefix' => 'news'], function()
// {
// 	Route::get('', [
// 		'as' => 'main.news.index',
// 		'uses' => 'NewsController@getIndex',
// 	]);

// 	Route::get('{news_category_slug}/{news_slug}', [
// 		'as' => 'main.news.show',
// 		'uses' => 'NewsController@getShow',
// 	]);
// });

Route::group(['prefix' => 'emplacement'], function()
{
	Route::get('', [
		'as' => 'main.ad_placement.index',
		'uses' => 'AdPlacementController@getIndex',
	]);

	Route::get('{ad_placement}', [
		'as' => 'main.ad_placement.show',
		'uses' => 'AdPlacementController@getShow',
	]);
});

Route::group(['prefix' => 'authentification'], function()
{
	Route::post('inscription', [
		'as' => 'main.auth.signup',
		'uses' => 'Auth\AuthController@postSignup'
	]);

	Route::post('register', [
		'as' => 'main.auth.signup.form',
		'uses' => 'Auth\AuthController@postSignupForm'
	]);

	Route::post('login', [
		'as' => 'main.auth.login.post',
		'uses' => 'Auth\AuthController@postLogin'
	]);

	Route::get('logout', [
		'as' => 'main.auth.logout',
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
		'as' => 'main.password.email.reset',
	]);

	Route::post('reset', [
		'uses' => 'Auth\PasswordController@postReset',
		'as' => 'main.password.reset',
	]);

	Route::get('reset', [
		'uses' => 'Auth\PasswordController@getReset',
		'as' => 'main.password.form.reset',
	]);
});

Route::group(['middleware' => 'user.auth', 'prefix' => 'mon-compte'], function() {
	Route::get('', [
		'uses' => 'Account\HomeController@getIndex',
		'as' => 'main.account.home',
	]);

	Route::post('', [
		'uses' => 'Account\HomeController@postForm',
		'as' => 'main.account.home.post',
	]);

	Route::get('demandes-validees', [
		'uses' => 'Account\AdPlacementController@getAdPlacementsValid',
		'as' => 'main.account.ad_placements_valid',
	]);

	Route::get('demandes-en-attente', [
		'uses' => 'Account\AdPlacementController@getAdPlacementsPending',
		'as' => 'main.account.ad_placements_pending',
	]);

	Route::get('demandes-annulees', [
		'uses' => 'Account\AdPlacementController@getAdPlacementsCanceling',
		'as' => 'main.account.ad_placements_canceling',
	]);

	Route::get('selection', [
		'uses' => 'Account\AdPlacementController@getAdPlacementsSelection',
		'as' => 'main.account.ad_placements_selection',
	]);

	Route::get('factures', [
		'uses' => 'Account\InvoiceController@getInvoices',
		'as' => 'main.account.invoices',
	]);

	Route::get('facture/{invoice}', [
		'uses' => 'Account\InvoiceController@get',
		'as' => 'main.account.invoice',
	]);

	Route::get('coordonnees-bancaires', [
		'uses' => 'Account\PaymentController@getBankDetails',
		'as' => 'main.account.bank_details',
	]);

});

// Without Csrf validation
Route::group(['middleware' => 'user.auth', 'prefix' => 'api'], function() {

	Route::get('technical_support', [
		'uses' => 'Api\TechnicalSupportController@all',
		'as' => 'main.api.technical_support',
	]);

	Route::get('template', [
		'uses' => 'Api\TemplateController@all',
		'as' => 'main.api.template',
	]);

	Route::post('ad_placement/{ad_placement}/selection', [
		'uses' => 'Api\SelectionController@postAdPlacementSelection',
		'as' => 'main.api.ad_placement.selection.post',
	]);

	Route::delete('ad_placement/{ad_placement}/selection', [
		'uses' => 'Api\SelectionController@deleteAdPlacementSelection',
		'as' => 'main.api.ad_placement.selection.delete',
	]);

	Route::post('ad_placement/{ad_placement}/buy', [
		'uses' => 'Api\BuyController@postBuyAdPlacement',
		'as' => 'main.api.ad_placement.buy.post',
	]);

	Route::delete('ad_placement/{ad_placement}/buy', [
		'uses' => 'Api\BuyController@deleteOfferAdPlacement',
		'as' => 'main.api.ad_placement.offer.delete',
	]);

	Route::post('credit_card', [
		'uses' => 'Api\AccountController@postCreditCard',
		'as' => 'main.api.account.credit_card.post',
	]);

	Route::delete('credit_card', [
		'uses' => 'Api\AccountController@deleteCreditCard',
		'as' => 'main.api.account.credit_card.delete',
	]);

	Route::get('credit_card', [
		'uses' => 'Api\AccountController@getCreditCard',
		'as' => 'main.api.account.credit_card.get',
	]);
});


Route::group(['prefix' => 'api'], function() {

	Route::get('support/{support}/category', [
		'uses' => 'Api\CategoryController@getSupportCategories',
		'as' => 'main.api.support.categories',
	]);

	Route::get('support/{support}/format', [
		'uses' => 'Api\FormatController@getSupportFormats',
		'as' => 'main.api.support.formats',
	]);

	Route::get('support/{support}/theme', [
		'uses' => 'Api\ThemeController@getSupportThemes',
		'as' => 'main.api.support.themes',
	]);

	Route::post('contact', [
		'uses' => 'Api\ContactController@sendContact',
		'as' => 'main.api.contact',
	]);

	Route::get('ad_placement/{ad_placement}', [
		'uses' => 'Api\AdPlacementController@get',
		'as' => 'main.api.ad_placement.get',
	]);

	Route::post('ad_placement/{ad_placement}/share', [
		'uses' => 'Api\AdPlacementController@share',
		'as' => 'main.api.ad_placement.share',
	]);
});

Route::group(['prefix' => 'form'], function()
{
	Route::post('agency', [
		'as' => 'main.form.agency',
		'uses' => 'HomeController@agency',
	]);
});

Route::get('/styleguide', [
	'as' => 'main.styleguide',
	'uses' => 'StyleguideController@getStyleguide',
]);


Route::get('/article-page', function(){
	return view('main.news.articles-page');
});

Route::get('/faq', [
	function () {
    	return view('main.faq');
	},
	'as' => 'main.faq',
]);

Route::get('/mentions', [
	function(){
		return view('main.terms-and-conditions');
	},
	'as' => 'main.mentions',
]);

Route::get('/cgv', [
	function(){
		return view('main.selling-conditions');
	},
	'as' => 'main.cgv',
]);

Route::get('/cgv-studio-graphique', [
	function(){
		return view('main.traffic-conditions');
	},
	'as' => 'main.cgv-studio-graphique',
]);

Route::get('/invoices-details', [
	function(){
		return view('main.account.invoices-details');
	},
	'as' => 'main.account.invoices-details',
]);

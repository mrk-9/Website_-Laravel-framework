<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Validators\CustomValidator;

class CustomValidatorServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->app->validator->resolver(function($translator, $data, $rules, $messages)
		{
			return new CustomValidator($translator, $data, $rules, $messages);
		});
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}

<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdNetworkResetPasswordTest extends TestCase
{
	use DatabaseTransactions;

	protected static $valid_data;
	protected static $unvalid_data;

	 public function setUp()
    {
        parent::setUp();

        self::$valid_data = [
            "email" => "julian.didier@escaledigitale.com",
            "password" => "myPrettyPassword",
            "password_confirmation" => "myPrettyPassword",
        ];

        self::$unvalid_data = [
            "email" => "unknown@email.com",
            "password" => "myPrettyPassword",
            "password_confirmation" => "myPrettyPasswordIsNotChecked",
        ];
    }

   /**
     * Check if post reset password is valid
     */
    public function testPostResetEmailOk()
    {
    	Session::start();
    	self::$valid_data['_token'] = csrf_token();

    	$this->post(route('ad-network.password.email.reset'), self::$valid_data);
    	$this->assertResponseOk();

    	$this->seeInDatabase('password_resets', [
    		'email' => self::$valid_data['email'],
    	]);
    }

    /**
     * Check if post reset password is not valid
     */
    public function testPostResetEmailUserKo()
    {
    	Session::start();
    	self::$unvalid_data['_token'] = csrf_token();

    	$this->post(route('ad-network.password.email.reset'), self::$unvalid_data);
    	// There is a redirection if data doesn't match rules
    	$this->assertResponseStatus(302);
    }

    public function testPostResetPasswordOk()
    {
        Session::start();
        self::$valid_data['_token'] = csrf_token();
        self::$valid_data['token'] = "tokenForPasswordReset";

        DB::table('password_resets')->insert([
            'email' => self::$valid_data['email'],
            'token' => self::$valid_data['token'],
        ]);

        $this->post(route('ad-network.password.reset'), self::$valid_data);
        $this->assertResponseOk();
    }
}

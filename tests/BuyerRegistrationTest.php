<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BuyerRegistrationTest extends TestCase
{
	use DatabaseTransactions;

    protected static $referent_data;
    protected static  $buyer_data;

    public function setUp()
    {
        parent::setUp();

        self::$referent_data = [
            "title" => "M.",
            "family_name" => "Gates",
            "name" => "Bill",
            "function" => "CEO",
            "email" => "did.julian+referent@gmail.com",
            "phone" => "0202020202",
            "password" => "myVeryPrettyGoodPasswordHere",
            "password_confirmation" => "myVeryPrettyGoodPasswordHere"
        ];

        self::$buyer_data = [
            "buyer_type" => "agency",
            "buyer_company_type" => "SARL",
            "buyer_name" => "Alibaba-gator",
            "buyer_email" => "did.julian+agence@gmail.com",
            "buyer_phone" => "0707070707",
            "buyer_address" => "1 rue des champs",
            "buyer_zipcode" => "44000",
            "buyer_city" => "Nantes",
            "buyer_customers" => "quelques mandats here",
        ];
    }

    /**
     * This first part concerns the modal subscription
     */
    public function testNewBuyerSubscriptionPartOne()
    {
    	$this->visit(route('main.home'))
    		->select(self::$buyer_data['buyer_type'], 'buyer_type')
    		->type(self::$referent_data['email'], 'email')
    		->type(self::$referent_data['password'], 'password')
    		->type(self::$referent_data['password_confirmation'], 'password_confirmation')
    		->press('Valider') // the given button text. Be careful... can easily change
    		->seePageIs(route('main.auth.signup'));
    }

    /**
     * This second part concerns full form subscription
     * We test if response code is 200 and we test data presence in database.
     */
    public function testNewBuyerSubscriptionPartTwo()
    {
    	$datas = array_merge(self::$referent_data, self::$buyer_data);

    	Session::start();
    	$datas['_token'] = csrf_token();

    	$this->post(route('main.auth.signup.form'), $datas);
    	$this->assertResponseOk();
    	$this->seeInDatabase('user', ['email' => self::$referent_data['email']]);
    	$this->seeInDatabase('buyer', ['email' => self::$buyer_data['buyer_email']]);
    }
}

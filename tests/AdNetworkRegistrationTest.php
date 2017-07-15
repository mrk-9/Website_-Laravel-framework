<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdNetworkRegistrationTest extends TestCase
{
	use DatabaseTransactions;

    protected static $referent_data;
    protected static  $ad_network_data;

    public function setUp()
    {
        parent::setUp();

        self::$referent_data = [
            "title" => "M.",
            "family_name" => "Bill",
            "referent_name" => "Rahm",
            "position" => "CEO",
            "referent_email" => "bill.rahm@email.com",
            "referent_phone" => "0202020202",
            "password" => "myVeryPrettyGoodPasswordHere",
            "password_confirmation" => "myVeryPrettyGoodPasswordHere"
        ];

        self::$ad_network_data = [
            "name" => "BlankCorp",
            "corporate_name" => "BigCorp",
            "company_type" => "SARL",
            "email" => "blank.corp@email.com",
            "phone" => "0202020202",
            "address" => "1 rue des champs",
            "address2" => "2 rue des champs",
            "zipcode" => "44000",
            "city" => "Nantes",
            "supports" => 4,
        ];
    }

    /**
     * This second part concerns full form subscription
     * We test if response code is 200 and we test data presence in database.
     */
    public function testAdNetworkRegister()
    {
    	$datas = array_merge(self::$referent_data, self::$ad_network_data);

    	Session::start();
    	$datas['_token'] = csrf_token();

    	$this->post(route('ad-network.auth.register'), $datas);
    	$this->assertResponseOk();
    	$this->seeInDatabase('ad_network_user', ['email' => self::$referent_data['referent_email']]);
    	$this->seeInDatabase('ad_network', ['email' => self::$ad_network_data['email']]);
    }
}

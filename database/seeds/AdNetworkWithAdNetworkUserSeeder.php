<?php

use App\AdNetwork;
use App\AdNetworkUser;

class AdNetworkWithAdNetworkUserSeeder extends BaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('TRUNCATE ad_network_user RESTART IDENTITY CASCADE');
		DB::statement('TRUNCATE ad_network RESTART IDENTITY CASCADE');

		for ($i = 0; $i < count($this->users); $i++) {
			$ad_network = AdNetwork::create([
		        'name' => $this->faker->company,
		        'corporate_name' => $this->faker->company,
		        'company_type' => $this->company_types[array_rand($this->company_types)],
		        'address' => $this->faker->address,
		        'address2' => $this->address_empty_or_not()[array_rand($this->address_empty_or_not())],
		        'zipcode' => $this->faker->postcode,
		        'city' => $this->faker->city,
		        'phone' => $this->faker->phoneNumber,
		        'email' => $this->faker->email,
		        'supports' => 5,
		        'status' => $this->status[1], //valid status
		        'ad_network_user_id' => null,
		    ]);

			$ad_network_user = AdNetworkUser::create([
		        'name' => $this->users[$i]['first_name'] . ' ' . $this->users[$i]['family_name'],
				'email' => $this->users[$i]['email'],
				'password' =>  $this->users[$i]['password'],
		        'family_name' => $this->users[$i]['family_name'],
		        'title' => $this->faker->titleMale,
		        'position' => $this->faker->word($nb = 8),
		        'ad_network_id' => $ad_network->id,
		        'phone' => $this->faker->phoneNumber
		    ]);

		    $ad_network->ad_network_user_id = $ad_network_user->id;
		    $ad_network->save();
		}

		// for (count($this->users)+1; $i < 30; $i++) {
		//     $ad_network = AdNetwork::create([
		//         'name' => $this->faker->company,
		//         'corporate_name' => $this->faker->company,
		//         'company_type' => $this->company_types[array_rand($this->company_types)],
		//         'address' => $this->faker->address,
		//         'address2' => $this->address_empty_or_not()[array_rand($this->address_empty_or_not())],
		//         'zipcode' => $this->faker->postcode,
		//         'city' => $this->faker->city,
		//         'phone' => $this->faker->phoneNumber,
		//         'email' => $this->faker->email,
		//         'supports' => 5,
		//         'status' => $this->status[array_rand($this->status)],
		//         'ad_network_user_id' => null,
		//     ]);

		//     $ad_network_user = AdNetworkUser::create([
		//         'name' => $this->faker->lastName,
		//         'family_name' => $this->faker->firstNameMale,
		//         'title' => $this->faker->titleMale,
		//         'email' => $this->faker->email,
		//         'password' => 'abcdefghij',
		//         'position' => $this->faker->word($nb = 8),
		//         'ad_network_id' => $ad_network->id,
		//         'phone' => $this->faker->phoneNumber
		//     ]);

		//     $ad_network->ad_network_user_id = $ad_network_user->id;
		//     $ad_network->save();
		// }
	}

}

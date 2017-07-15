<?php

use App\Buyer;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UserBuyerSeeder extends BaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('TRUNCATE "user" RESTART IDENTITY CASCADE');
		DB::statement('TRUNCATE buyer RESTART IDENTITY CASCADE');

		for ($i = 0; $i < count($this->users); $i++) {
			$buyer = Buyer::create([
				'name' => $this->faker->company,
				'company_type' => $this->company_types[array_rand($this->company_types)],
				'address' => $this->faker->address,
				'zipcode' => $this->faker->postcode,
				'city' => $this->faker->city,
				'phone' => $this->faker->phoneNumber,
				'email' => $this->faker->email,
				'status' => "valid",
				'activity' => $this->faker->sentence($nbWords = 6),
				'type' => $this->type[array_rand($this->type)],
				'user_id' => null
			]);

			$user = User::create([
				'name' => $this->users[$i]['first_name'],
				'family_name' => $this->users[$i]['family_name'],
				'title' => $this->faker->titleMale,
				'function' => $this->faker->word,
				'email' => $this->users[$i]['email'],
				'phone' => $this->faker->phoneNumber,
				'password' => $this->users[$i]['password'],
				'buyer_id' => $buyer->id
			]);

			$buyer->user_id = $user->id;
			$buyer->save();
		}

		// for ($i = 0; $i < 30; $i++) {
		// 	$buyer = Buyer::create([
		// 		'name' => $this->faker->company,
		// 		'company_type' => $this->company_types[array_rand($this->company_types)],
		// 		'address' => $this->faker->address,
		// 		'zipcode' => $this->faker->postcode,
		// 		'city' => $this->faker->city,
		// 		'phone' => $this->faker->phoneNumber,
		// 		'email' => $this->faker->email,
		// 		'status' => Buyer::STATUS_VALID,
		// 		'activity' => $this->faker->sentence($nbWords = 6),
		// 		'type' => $this->type[array_rand($this->type)],
		// 		'user_id' => null
		// 	]);

		// 	$user = User::create([
		// 		'name' => $this->faker->firstNameMale,
		// 		'family_name' => $this->faker->lastName,
		// 		'title' => $this->faker->titleMale,
		// 		'function' => $this->faker->word,
		// 		'email' => $this->faker->safeEmail,
		// 		'phone' => $this->faker->phoneNumber,
		// 		'password' => 'abcdefghij',
		// 		'buyer_id' => $buyer->id
		// 	]);

		// 	$buyer->user_id = $user->id;
		// 	$buyer->save();
		// }
	}

}

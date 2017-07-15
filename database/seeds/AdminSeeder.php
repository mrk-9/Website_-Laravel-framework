<?php

use App\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AdminSeeder extends BaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

		DB::statement('TRUNCATE admin RESTART IDENTITY CASCADE');

		for ($i = 0; $i < count($this->users); $i++) {
			Admin::create([
				'name' => $this->users[$i]['first_name'] . ' ' . $this->users[$i]['family_name'],
				'email' => $this->users[$i]['email'],
				'password' =>  $this->users[$i]['password'],
			]);
		}

	}

}

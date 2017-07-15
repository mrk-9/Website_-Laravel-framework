<?php

use App\Frequency;
use Illuminate\Database\Eloquent\Model;

class FrequencySeeder extends BaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('TRUNCATE frequency RESTART IDENTITY CASCADE');

		Frequency::create([
			'name' => 'Quotidien'
		]);

		Frequency::create([
			'name' => 'Hebdomadaire'
		]);

		Frequency::create([
			'name' => 'Bimensuel'
		]);

		Frequency::create([
			'name' => 'Mensuel'
		]);

		Frequency::create([
			'name' => 'Bimestriel'
		]);

		Frequency::create([
			'name' => 'Trimestriel'
		]);

		Frequency::create([
			'name' => 'Semestriel'
		]);

		Frequency::create([
			'name' => 'Annuel'
		]);

		Frequency::create([
			'name' => 'Autre'
		]);
	}
}

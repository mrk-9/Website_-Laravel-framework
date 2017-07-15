<?php

use App\Offer;
use App\AdPlacement;

class OfferSeeder extends BaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('TRUNCATE offer RESTART IDENTITY CASCADE');

		/*for ($i = 1; $i < AdPlacement::count(); $i++) {
			if (AdPlacement::find($i)->type == 'offer') {
				$offer = Offer::create([
					'user_id' => $i,
					'ad_placement_id' => $i,
					'amount' => $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 10.23, $max = 243.58)
				]);
			}
		}*/
	}
}



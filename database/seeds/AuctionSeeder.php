<?php

use App\AdPlacement;
use App\Auction;

class AuctionSeeder extends BaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('TRUNCATE auction RESTART IDENTITY CASCADE');

		/*for ($i = 1; $i < AdPlacement::count(); $i++) {
			if (AdPlacement::find($i)->type == 'auction') {
				$auction = Auction::create([
					'user_id' => $i,
					'ad_placement_id' => $i,
					'amount' => $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 10.23, $max = 243.58)
				]);
			}
		}*/
	}

}

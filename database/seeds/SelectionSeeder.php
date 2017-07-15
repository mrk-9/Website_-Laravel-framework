<?php

use App\Selection;
use App\AdPlacement;

class SelectionSeeder extends BaseSeeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('TRUNCATE selection RESTART IDENTITY CASCADE');

		for ($i = 1; $i < AdPlacement::count(); $i++) {
			$ad_placement = AdPlacement::find($i);
			$selection = Selection::create([
				'user_id' => $i,
				'ad_placement_id' => $ad_placement->id,
			]);
		}
	}
}

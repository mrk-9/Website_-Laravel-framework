<?php

use App\BroadcastingArea;

class BroadcastingAreaSeeder extends BaseSeeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('TRUNCATE broadcasting_area RESTART IDENTITY CASCADE');

		BroadcastingArea::create([
			'name' => "France entière",
			'slug' => "all"
		]);

		foreach ($this->broadcasting_areas as $broadcasting_area) {
			BroadcastingArea::create([
				'name' => $broadcasting_area,
			]);
		}
	}

}

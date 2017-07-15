<?php

use App\Theme;

class ThemeSeeder extends BaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('TRUNCATE theme RESTART IDENTITY CASCADE');

		foreach ($this->themes as $theme) {
			Theme::create([
				'name' => $theme,
				'support_id' => 2
			]);
		}
	}

}

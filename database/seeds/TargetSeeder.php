<?php

use App\Target;

class TargetSeeder extends BaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('TRUNCATE target RESTART IDENTITY CASCADE');

		foreach ($this->targets as $target) {
			Target::create([
				'name' => $target,
			]);
		}
	}

}

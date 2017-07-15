<?php

use App\Support;
use Illuminate\Database\Eloquent\Model;

class SupportSeeder extends BaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('TRUNCATE support RESTART IDENTITY CASCADE');

		foreach ($this->supports as $support) {
			Support::create([
				'name' => $support,
			]);
		}
	}

}

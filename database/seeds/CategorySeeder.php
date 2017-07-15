<?php

use App\Category;
use App\Support;

class CategorySeeder extends BaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('TRUNCATE category RESTART IDENTITY CASCADE');

		foreach ($this->categories as $category) {
		    Category::create([
		        'name' => $category,
		        'support_id' => 2
		    ]);
		}
	}
}

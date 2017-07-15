<?php

use App\Format;

class FormatSeeder extends BaseSeeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('TRUNCATE format RESTART IDENTITY CASCADE');

        foreach ($this->formats as $format) {
            Format::create([
                'name' => $format["name"],
                'support_id' => $format["support_id"]
            ]);
        }
    }

}

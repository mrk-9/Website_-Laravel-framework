<?php

use App\TechnicalSupport;

class TechnicalSupportSeeder extends BaseSeeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('TRUNCATE technical_support RESTART IDENTITY CASCADE');

        foreach ($this->technical_supports as $technical_support) {
            $technical_support = TechnicalSupport::create([
                'name' => $technical_support["name"],
                'description' => $technical_support["description"],
                'price' => $technical_support["price"],
            ]);

            // We need to set a special slug to avoid bugs
            // with our search engine
            if ($technical_support->id === 1) {
                $technical_support->slug = "template";
                $technical_support->save();
            }
        }
    }



}

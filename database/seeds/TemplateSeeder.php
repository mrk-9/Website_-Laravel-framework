<?php

use App\Template;

class TemplateSeeder extends BaseSeeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('TRUNCATE template RESTART IDENTITY CASCADE');

        foreach ($this->templates as $template) {
            Template::create([
                'name' => $template['name'],
                'description' => $template['description'],
                'cover' => $template['cover']
            ]);
        }
    }

}

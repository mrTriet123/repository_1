<?php

use Illuminate\Database\Seeder;

use App\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::create(['code' => 'en', 'name' => 'English']);
        Language::create(['code' => 'zh', 'name' => 'Chinese']);
        Language::create(['code' => 'jp', 'name' => 'Japanese']);
    }
}

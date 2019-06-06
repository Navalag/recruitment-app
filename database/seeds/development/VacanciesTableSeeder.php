<?php

use Illuminate\Database\Seeder;
use App\Vacancy;

class VacanciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Vacancy::class, 5)->create();
    }
}

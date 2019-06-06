<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
             ApplicantsTableSeeder::class,
             VacanciesTableSeeder::class,
             UsersTableSeeder::class,
         ]);
    }
}

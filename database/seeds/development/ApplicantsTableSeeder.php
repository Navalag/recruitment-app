<?php

use Illuminate\Database\Seeder;
use App\Applicant;

class ApplicantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Applicant::class, 50)->create();
    }
}

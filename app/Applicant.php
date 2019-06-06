<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $guarded = [];

    protected $dates = ['start_test_time', 'finish_test_time'];

    public function jobAppliedFor()
    {
        return $this->hasOne('App\Vacancy', 'id', 'vacancy_id');
    }
}

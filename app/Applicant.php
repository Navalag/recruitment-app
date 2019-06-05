<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $guarded = [];

    public function jobAppliedFor()
    {
        return $this->hasOne('App\Vacancy', 'id', 'vacancy_id');
    }
}

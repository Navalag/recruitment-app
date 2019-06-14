<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $dates = ['start_test_time', 'finish_test_time'];

    public function path()
    {
        return "/applicant/{$this->id}";
    }

    public function jobAppliedFor()
    {
        return $this->hasOne('App\Vacancy', 'id', 'vacancy_id');
    }
}

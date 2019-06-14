<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacancy extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::addGlobalScope('applicantCount', function ($builder){
            $builder->withCount('applicants');
        });

        static::deleting(function($vacancy) {
            $vacancy->applicants()->delete();
        });
    }

    public function applicants()
    {
        return $this->hasMany('App\Applicant');
    }
}

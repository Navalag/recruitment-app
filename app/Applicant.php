<?php

namespace App;

use App\Filters\ApplicantFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Applicant extends Model
{
    use SoftDeletes, SearchableTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_test_time', 'finish_test_time'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'applicants.first_name' => 10,
            'applicants.last_name' => 10,
            'applicants.phone_number' => 10,
            'applicants.email' => 10,
            'applicants.status' => 2,
            'vacancies.job_title' => 5,
        ],
        'joins' => [
            'vacancies' => ['applicants.vacancy_id','vacancies.id'],
        ],
    ];

    /**
     * Get a string path for the thread.
     *
     * @return string
     */
    public function path()
    {
        return "/applicant/{$this->id}";
    }

    /**
     * Applicant belongs to one vacancy
     *
     * @return HasOne
     */
    public function jobAppliedFor()
    {
        return $this->hasOne('App\Vacancy', 'id', 'vacancy_id');
    }

    /**
     * Apply all relevant thread filters.
     *
     * @param  Builder          $query
     * @param  ApplicantFilters $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, ApplicantFilters $filters)
    {
        return $filters->apply($query);
    }
}

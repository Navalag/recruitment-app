<?php

namespace App;

use App\Filters\VacancyFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vacancy extends Model
{
    use SoftDeletes, SearchableTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

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
            'vacancies.job_title' => 10,
            'vacancies.email_subject' => 10,
            'vacancies.email_body' => 5,
            'vacancies.time_for_task' => 5,
        ]
    ];

    /**
     * Boot the model.
     */
    public static function boot() {
        parent::boot();

        static::addGlobalScope('applicantCount', function ($builder){
            $builder->withCount('applicants');
        });

        static::deleting(function($vacancy) {
            $vacancy->applicants()->delete();
        });
    }

    /**
     * A vacancy may have many applicants.
     *
     * @return HasMany
     */
    public function applicants()
    {
        return $this->hasMany('App\Applicant');
    }

    /**
     * Apply all relevant vacancy filters.
     *
     * @param  Builder          $query
     * @param  VacancyFilters $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, VacancyFilters $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Scope a query to only include active vacancies.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active_status', 1);
    }
}

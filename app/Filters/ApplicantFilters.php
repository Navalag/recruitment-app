<?php

namespace App\Filters;

use App\Applicant;
use App\Vacancy;
use Illuminate\Database\Eloquent\Builder;

class ApplicantFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['status', 'position'];

    /**
     * Filter the query by a given username.
     *
     * @param  integer $vacancyId
     * @return Builder
     */
    protected function position($vacancyId)
    {
        return $this->builder->where('vacancy_id', $vacancyId);
    }

    /**
     * Filter the query according to most popular threads.
     *
     * @param  string $status
     * @return Builder
     */
    protected function status($status)
    {
        return $this->builder->where('status', $status);
    }
}

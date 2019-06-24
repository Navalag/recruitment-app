<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ApplicantFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['status', 'position', 'search'];

    /**
     * Filter the query by a given position.
     *
     * @param  integer $vacancyId
     * @return Builder
     */
    protected function position($vacancyId)
    {
        return $this->builder->where('vacancy_id', $vacancyId);
    }

    /**
     * Filter the query according to applicant status.
     *
     * @param  string $status
     * @return Builder
     */
    protected function status($status)
    {
        return $this->builder->where('status', $status);
    }

    /**
     * Search everything in Applicant model.
     *
     * @param  string $query
     * @return Builder
     */
    protected function search($query)
    {
        return $this->builder->search($query);
    }
}

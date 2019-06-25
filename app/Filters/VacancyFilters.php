<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class VacancyFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['search'];

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

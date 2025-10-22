<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class UserIdNameFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('user', function ($q) use ($value) {
            $q->where('name', 'like', "%{$value}%");
        });
    }
}

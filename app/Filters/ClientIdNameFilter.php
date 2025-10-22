<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ClientIdNameFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('client', function ($q) use ($value) {
            $q->where('name', 'like', "%{$value}%");
        });
    }
}

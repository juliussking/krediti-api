<?php
namespace App\Filters;


use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ClientGlobalSearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            $query->where('name', 'like', "%{$value}%")
                  ->orWhere('email', 'like', "%{$value}%");
        });
    }
}

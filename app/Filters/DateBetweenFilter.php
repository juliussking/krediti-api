<?php

namespace App\Filters;

use Carbon\Carbon;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class DateBetweenFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if (!is_array($value)) {
            return;
        }

        $start = $value['start'] ?? null;
        $end = $value['end'] ?? null;


        if ($start && $end && $start === $end) {
            $query->whereDate($property, '=', Carbon::parse($start)->toDateString());
            return;
        }

        if ($start && $end) {
            $query->whereBetween($property, [$start, $end]);
            return;
        }

        if ($start && !$end) {
            $query->whereDate($property, '>=', $start);
            return;
        }

        if ($end && !$start) {
            $query->whereDate($property, '<=', $end);
            return;
        }
    }
}

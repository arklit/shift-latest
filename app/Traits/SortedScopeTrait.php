<?php

    namespace App\Traits;

    use Illuminate\Database\Eloquent\Builder;

    trait SortedScopeTrait
    {
        public function scopeSorted(Builder $query, $direction = 'desc'): Builder
        {
            return $query->orderBy('sort', $direction);
        }
    }

<?php

namespace App\Models;

use App\Orchid\Filters\DateCreatedFilter;
use App\Orchid\Filters\IsActiveFilter;
use App\Traits\IsActiveScopeTrait;
use App\Traits\SortedScopeTrait;

class Seo extends ProtoModel
{
    public const TABLE_NAME = 'seos';
    protected $table = self::TABLE_NAME;
    protected $allowedSorts = ['id', 'is_active', 'sort', 'title', 'created_at'];
    protected $allowedFilters = [
        'id',
        'is_active' => IsActiveFilter::class,
        'title',
        'code',
        'sort',
        'created_at' => DateCreatedFilter::class,
    ];

    use IsActiveScopeTrait;
    use SortedScopeTrait;
}


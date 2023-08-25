<?php

namespace App\Models;

use App\Traits\IsActiveScopeTrait;
use App\Traits\SortedScopeTrait;

class Seo extends ProtoModel
{
    public const TABLE_NAME = 'seos';
    protected $table = self::TABLE_NAME;
    protected $allowedSorts = ['id', 'is_active', 'sort', 'title', 'created_at'];
    protected $allowedFilters = ['id', 'title', 'code', 'sort'];

    use IsActiveScopeTrait;
    use SortedScopeTrait;
}


<?php

namespace App\Models;

use App\Traits\IsActiveScopeTrait;
use App\Traits\SortedScopeTrait;
use App\Traits\CodeScopeTrait;

class StaticPage extends ProtoModel
{
    public const TABLE_NAME = 'static_pages';
    protected $table = self::TABLE_NAME;
    protected $with = ['parent'];
    protected $allowedSorts = ['id', 'is_active', 'sort', 'title', 'code', 'created_at'];

    use IsActiveScopeTrait;
    use SortedScopeTrait;
    use CodeScopeTrait;

    public function parent()
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }
}

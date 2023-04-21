<?php

namespace App\Models;

use App\Orchid\RocontModule\Traits\IsActiveScopeTrait;
use App\Orchid\RocontModule\Traits\SortedScopeTrait;
use App\Traits\CodeScopeTrait;

class StaticPage extends ProtoModel
{
    public const TABLE_NAME = 'static_pages';
    protected $table = self::TABLE_NAME;
    protected $allowedSorts = ['id', 'is_active', 'sort', 'title', 'code', 'created_at'];

    use IsActiveScopeTrait;
    use SortedScopeTrait;
    use CodeScopeTrait;

    public function getTitle()
    {
        return $this->title;
    }
}

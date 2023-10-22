<?php

namespace App\Models;

use App\Orchid\Filters\DateCreatedFilter;
use App\Orchid\Filters\IsActiveFilter;
use App\Traits\CodeScopeTrait;
use App\Traits\IsActiveScopeTrait;
use App\Traits\SortedScopeTrait;
use Orchid\Filters\Types\Like;
use Termwind\Components\Li;

class StaticPage extends ProtoModel
{
    public const TABLE_NAME = 'static_pages';
    public const CODE_ABOUT = 'about';
    public const CODE_DELIVERY = 'delivery';
    public const CODE_CONTACTS = 'contacts';
    public const CODE_COOPERATION = 'cooperation';
    protected $table = self::TABLE_NAME;
    protected $with = ['parent'];
    protected $allowedSorts = ['is_active', 'sort', 'title', 'code', 'created_at'];
    protected $allowedFilters = [
        'is_active' => IsActiveFilter::class,
        'sort' => Like::class,
        'title' => Like::class,
        'code' => Like::class,
        'created_at' => DateCreatedFilter::class
    ];

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

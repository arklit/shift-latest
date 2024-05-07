<?php

namespace App\Models;

use App\Orchid\Filters\DateCreatedFilter;
use App\Orchid\Filters\IsActiveFilter;
use App\Traits\CodeScopeTrait;
use App\Traits\IsActiveScopeTrait;
use App\Traits\SortedScopeTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Types\Like;

class ModelName extends ProtoModel
{
    use IsActiveScopeTrait;
    use SortedScopeTrait;
    use CodeScopeTrait;

    public const TABLE_NAME = 'model_table';
    protected $table = self::TABLE_NAME;
    protected $allowedSorts = [//..allowedSorts];
    protected $allowedFilters = [//..allowedFilters];

    /*public function proto_relation(): HasMany
    {
        return $this->hasMany(ProtoModel::class, 'proto_foreign_key', 'id');
    }*/
}

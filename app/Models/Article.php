<?php

namespace App\Models;

use App\Orchid\RocontModule\Traits\IsActiveScopeTrait;
use App\Orchid\RocontModule\Traits\ReadyToPublicationScopeTrait;
use App\Orchid\RocontModule\Traits\SortedByPublicationScopeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends ProtoModel
{
    public const TABLE_NAME = 'articles';
    protected $table = self::TABLE_NAME;
    protected $allowedSorts = ['id', 'is_active', 'category_id', 'sort', 'title', 'slug', 'created_at'];

    use IsActiveScopeTrait;
    use ReadyToPublicationScopeTrait;
    use SortedByPublicationScopeTrait;

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id', 'id');
    }

    public function getTitle()
    {
        return $this->title;
    }
}


<?php

namespace App\Models;


use App\Traits\IsActiveScopeTrait;
use App\Traits\ReadyToPublicationScopeTrait;
use App\Traits\SortedByPublicationScopeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

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

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug(string $slug)
    {
        $this->slug = Str::slug($this->id . '-' . $slug);
        return $this;
    }
}


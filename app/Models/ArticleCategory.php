<?php

namespace App\Models;

use App\Traits\IsActiveScopeTrait;
use App\Traits\SortedScopeTrait;
use App\Traits\CodeScopeTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleCategory extends ProtoModel
{
    public const TABLE_NAME = 'article_categories';
    protected $table = self::TABLE_NAME;
    protected $allowedSorts = ['id', 'is_active', 'sort', 'title', 'code', 'created_at'];

    use IsActiveScopeTrait;
    use SortedScopeTrait;
    use CodeScopeTrait;

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id', 'id');
    }
}

<?php

namespace App\Models;


use App\Traits\IsActiveScopeTrait;
use App\Traits\ReadyToPublicationScopeTrait;
use App\Traits\SortedByPublicationScopeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Article extends ProtoModel
{
    use IsActiveScopeTrait;
    use ReadyToPublicationScopeTrait;
    use SortedByPublicationScopeTrait;

    public const TABLE_NAME = 'articles';
    public const PER_PAGE = 10;

    protected $table = self::TABLE_NAME;
    protected array $allowedSorts = ['id', 'is_active', 'category_id', 'sort', 'title', 'slug', 'created_at', 'publication_date'];
    protected array $allowedFilters = ['id', 'title', 'slug', 'created_at', 'publication_date'];

    protected $casts = [
        'publication_date' => 'datetime'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id', 'id');
    }

    public function setSlug(string $slug): static
    {
        $this->slug = Str::slug($this->id . '-' . $slug);
        return $this;
    }

    public function getDateFormatted()
    {
        $date = Carbon::make($this->publication_date);
        return $date->isoFormat("DD.MM.YYYY");
    }
}


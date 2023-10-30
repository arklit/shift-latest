<?php

namespace App\Models;

use App\Orchid\Traits\ActiveScopeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InformationPage extends ProtoModel
{
    public const TABLE_NAME = 'information_pages';
    protected $table = self::TABLE_NAME;
    protected $allowedSorts     = ['is_active', 'type', 'parent_id', 'uri', 'created_at'];
    protected $allowedFilters   = ['is_active', 'type', 'parent_id', 'uri', 'created_at'];

    use ActiveScopeTrait;

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->active();
    }

    public function parent()
    {
        return $this->hasOne(self::class, 'id', 'parent_id')->active();
    }

    public function getActivityStatusAttribute(): string
    {
        return $this->is_active ? 'Да' : 'Нет';
    }

    public function getImage()
    {
//        return->$this->attachment()
    }

    public function getUpdateRoute($item): string
    {
        return $item === 'link'
            ? $this->updateRoute = 'platform.information-link.edit'
            : $this->updateRoute = 'platform.information-page.edit';
    }

    public function getParentCode($item)
    {
        $query = InformationPage::query()->where('id', $item->parent_id)->first();

        return !empty($item->parent_id) ? $query->code : 'Нет родителя';
    }

    public function getBreadcrumbs($breadcrumbs = [])
    {
        array_unshift($breadcrumbs, $this);

        if ($this->parent_id) {
            $parentPage = InformationPage::find($this->parent_id);
            $breadcrumbs = $parentPage->getBreadcrumbs($breadcrumbs);
        }

        return $breadcrumbs;
    }
}

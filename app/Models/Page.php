<?php

namespace App\Models;

use App\Orchid\Traits\ActiveScopeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Breadcrumbs;
use Tabuna\Breadcrumbs\Trail;

class Page extends ProtoModel
{
    use ActiveScopeTrait;

    public const TABLE_NAME = 'information_pages';
    protected $table = self::TABLE_NAME;
    protected $allowedSorts     = ['is_active', 'type', 'parent_id', 'uri', 'created_at'];
    protected $allowedFilters   = ['is_active', 'type', 'parent_id', 'uri', 'created_at'];

    protected $casts = [
        'data' => 'array'
    ];

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->active();
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function parent()
    {
        return $this->hasOne(self::class, 'id', 'parent_id')->active();
    }

    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
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
        return match ($item) {
            'link' => 'platform.information-link.edit',
            'page' => 'platform.information-page.edit',
            'sample' => 'platform.information-sample.edit',
        };
    }

    private function getParentsList(array &$list, ?Page $parentPage)
    {
        if (is_null($parentPage)) {
            return;
        }
        $list[] = $this->setDataForBreadCrumbs($parentPage);

        $this->getParentsList($list, $parentPage->parentRecursive);
    }

    private function setDataForBreadCrumbs(Page $page)
    {
        return [
            'title' => $page->name,
            'route' => 'web.pages.list',
            'params' => $page->uri
        ];
    }

    public function setBreadCrumbs()
    {
        $crumbs = [];
        $this->getParentsList($crumbs, $this->parentRecursive()->first());

        $crumbs = array_reverse($crumbs);
        $crumbs[] = $this->setDataForBreadCrumbs($this);

        $this->setCrumbs($crumbs);
    }

    public function getBreadCrumbsTest(Trail $t, array $crumbs)
    {
        $t->parent('web.main.page');
        foreach ($crumbs as $crumb) {
            $t->push($crumb['title'], route($crumb['route'], $crumb['params'] ?? []));
        }
        return $t;
    }

    public function setCrumbs($crumbs): void
    {
        Breadcrumbs::for(Route::currentRouteName(), fn(Trail $t) => $this->getBreadCrumbsTest($t, $crumbs));
    }
}

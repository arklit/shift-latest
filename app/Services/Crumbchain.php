<?php

namespace App\Services;

use App\DTO\CrumbDTO;
use App\Models\StaticPage;
use Illuminate\Support\Collection;
use JsonSerializable;

class Crumbchain implements JsonSerializable
{
    public const ROUTE = 'web.pages.static';
    private static $instance;
    private Collection $storage;

    public function __construct()
    {
        $this->storage = collect();
    }

    /**
     * Create self or get existed instance
     * @return static
     */
    public static function cs(): static
    {
        return static::$instance ?? (static::$instance = new static());
    }

    public static function makeCrumb(string $title, string $url, bool $isActive = true): static
    {
        static::cs()->addCrumb(CrumbDTO::make($title, $url, $isActive));
        return static::$instance;
    }

    public static function makeParentsChain(StaticPage $page)
    {
        if (!is_null($page->getRelation('parent'))) {
            $dto = CrumbDTO::make($page->title, route(self::ROUTE, $page->code));
            self::makeParentsChain($page->parent);
            static::cs()->addCrumb($dto);
        } else {
            static::makeCrumb('Главная', route('web.main.page'));
        }
    }

    public function addCrumb(CrumbDTO $crumb): static
    {
        $this->storage->add($crumb);
        return $this;
    }

    public function getCrumbs()
    {
        return $this->storage;
    }

    public function jsonSerialize(): array
    {
        return [
            'crumbs' => $this->storage->map(fn($c) => $c->jsonSerialize()),
        ];
    }
}

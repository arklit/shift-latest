<?php

namespace App\Orchid\Screens\Pages;

use App\Enums\OrchidRoutes;
use App\Enums\PagesTypes;
use App\Models\Page;
use App\Orchid\Abstractions\EditScreenPattern;
use App\Orchid\Helpers\OrchidValidator;
use App\Orchid\Traits\CommandBarDeletableTrait;
use App\Services\GetUriService;
use Illuminate\Http\Request;
use Orchid\Screen\Layouts\Rows;

class PageEdit extends EditScreenPattern
{
    protected ?string $listRedirect = 'information.page.list';
    protected string $deleteMessage = 'Запись успешно удалена';
    protected string $titleName = 'name';
    protected ?Rows $layout = null;

    use CommandBarDeletableTrait;

    public function __construct()
    {
        $this->route = OrchidRoutes::INFO_PAGES;
    }

    public function query(Page $item)
    {
        $pageType = PagesTypes::from($item->type);
        $this->layout = $pageType->getLayout();
        $this->updateTitle = $pageType->getEditTitle();

        return $this->queryMake($item);
    }

    public function layout(): iterable
    {
        return [
            $this->layout,
        ];
    }

    public function save(Page $item, Request $request)
    {
        $data = $request->input('item');
        $payload = $data['data'] ?? [];
        $layout = PagesTypes::from($item->type)->getLayout();

        $validator = (new OrchidValidator($data, []))->setIndividualRules($layout->getRules(), $layout->getMessages())
            ->setUniqueFields($item, ['code' => 'Такой код уже используется'])
            ->validate();

        $data['uri'] = (new GetUriService())->getUri($data);

        if ($validator->isFail()) {
            return $validator->showErrors($this->route, $item->id);
        }

        return $this->saveItem($item, $data);
    }

    public function remove(Page $item)
    {
        return $this->removeItem($item);
    }

    public function getRules(string $code): array
    {
        $base = ['bail', 'required'];
        return match ($code) {
            PagesTypes::PAGE_ABOUT => [
                'title' => $base,
            ],
            PagesTypes::PAGE_CONTACTS => [
                'title' => $base,
            ],
            PagesTypes::PAGE_COOPERATION => [
                'title' => $base,
            ],
            default => [],
        };
    }

    public function getMessages(string $code): array
    {
        return match ($code) {
            PagesTypes::PAGE_ABOUT => [
                'title.required' => 'Введите название страницы',
            ],
            PagesTypes::PAGE_CONTACTS => [
                'title.required' => 'Введите название страницы',
            ],
            PagesTypes::PAGE_COOPERATION => [
                'title.required' => 'Введите название страницы',
            ],
            default => [],
        };
    }
}

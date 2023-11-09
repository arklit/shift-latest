<?php

namespace App\Orchid\Screens\Pages;

use App\Enums\OrchidRoutes;
use App\Enums\PagesTypes;
use App\Models\Page;
use App\Orchid\Abstractions\EditScreenPattern;
use App\Orchid\Helpers\OrchidValidator;
use App\Orchid\Traits\CommandBarDeletableTrait;
use Illuminate\Http\Request;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Alert;

class PageEdit extends EditScreenPattern
{
    protected ?string $listRedirect = 'platform.pages.list';
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
        $layout = PagesTypes::from($item->type)->getLayout();

        $validator = (new OrchidValidator($data, []))->setIndividualRules($layout->getRules(), $layout->getMessages())
            ->setUniqueFields($item, ['code' => 'Такой код уже используется'])
            ->validate();

        if ($validator->isFail()) {
            return $validator->showErrors($this->route, $item->id);
        }

        return $this->saveItem($item, $data);
    }

    public function remove(Page $item)
    {
        if ($item->removableChildren()->count() > 0)
        {
            Alert::error('Родительскую страницу нельзя удалить пока к ней привязаны дочерние!');
            return redirect()->route($this->listRedirect, $this->redirectParams);
        }
        return $this->removeItem($item);
    }
}

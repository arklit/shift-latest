<?php

namespace App\Orchid\Screens\Product;

use App\Enums\OrchidRoutes;
use App\Models\Product;
use App\Orchid\Abstractions\EditScreenPattern;
use App\Orchid\Fields\Cropper;
use App\Orchid\Helpers\OrchidValidator;
use App\Orchid\Screens\Modals\EmptyModal;
use App\Traits\CommandBarDeletableTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ProductEditScreen extends EditScreenPattern
{
    protected string $createTitle = 'Создание товари';
    protected string $updateTitle = 'Редактирование товари';

    use CommandBarDeletableTrait;

    public function __construct()
    {
        $this->route = OrchidRoutes::PRODUCT;
        $this->routeName = $this->route->list();
    }

    public function query(Product $item): array
    {
        return $this->queryMake($item);
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Group::make([
                    CheckBox::make('item.is_active')->placeholder('Активность')->sendTrueOrFalse()->value(true),
                    Input::make('item.title')->title('Название')->required(),
                    Input::make('item.code')->title('Код'),
                    Input::make('item.sort')->title('Сортировка')->type('number')->value(0)->required(),
                    
                ]),
            ]),
            Layout::modal('deleteItem', EmptyModal::class)->title('Уверены, что хотите удалить запись?')
                ->applyButton('Да')->closeButton('Нет'),
        ];
    }

    public function asyncGetItem(Product $item)
        {
            return [
                'item' => $item,
            ];
        }

    public function save(Product $item, Request $request)
    {
        $data = $request->input('item');

        if (empty($data['code'])) {
            $data['code'] = Str::slug($data['title']);
        }

        $validator = (new OrchidValidator($data))->setIndividualRules($this->getRules(), $this->getMessages())
            ->setUniqueFields($item, ['code' => 'Такой код уже используется'])
            ->validate();

        return $validator->isFail() ? $validator->showErrors($this->route, $item->id) : $this->saveItem($item, $data);
    }

    public function remove(Product $item): RedirectResponse
    {
        /*if ($item->relation()->count() !== 0) {
            Alert::error('Этот элемент не является пустым. Его нельзя удалить');
            return redirect()->route($this->route->edit(), ['item' => $item->id]);
        }*/

        return $this->removeItem($item);
    }

    public function getRules(): array
    {
        return [

        ];
    }

    public function getMessages(): array
    {
        return [

        ];
    }
}


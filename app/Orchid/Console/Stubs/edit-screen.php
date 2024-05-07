<?php

namespace App\Orchid\Screens\DirectoryName;

use App\Enums\OrchidRoutes;
use App\Models\ProtoModel;
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

class StubEditScreen extends EditScreenPattern
{
    protected string $createTitle = 'Создание ITEM';
    protected string $updateTitle = 'Редактирование ITEM';

    use CommandBarDeletableTrait;

    public function __construct()
    {
        $this->route = OrchidRoutes::PROTO_MODEL;
        $this->routeName = $this->route->list();
    }

    public function query(ProtoModel $item): array
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
                    //..fields
                ]),
            ]),
            Layout::modal('deleteItem', EmptyModal::class)->title('Уверены, что хотите удалить запись?')
                ->applyButton('Да')->closeButton('Нет'),
        ];
    }

    public function asyncGetItem(ProtoModel $item)
        {
            return [
                'item' => $item,
            ];
        }

    public function save(ProtoModel $item, Request $request)
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

    public function remove(ProtoModel $item): RedirectResponse
    {
        /*if ($item->relation()->count() !== 0) {
            Alert::error('Этот элемент не является пустым. Его нельзя удалить');
            return redirect()->route($this->route->edit(), ['item' => $item->id]);
        }*/

        return $this->removeItem($item);
    }

    public function getRules(): array
    {
        return [];
    }

    public function getMessages(): array
    {
        return [];
    }
}


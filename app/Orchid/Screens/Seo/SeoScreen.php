<?php

namespace App\Orchid\Screens\Seo;

use App\Enums\OrchidRoutes;
use App\Enums\Permissions;
use App\Models\Seo;
use App\Orchid\Abstractions\ListScreenPattern;
use App\Orchid\Filters\DateCreatedFilter;
use App\Orchid\Filters\IsActiveFilter;
use App\Orchid\Helpers\OrchidHelper;
use App\Orchid\Layouts\EmptyModal;
use App\Orchid\Screens\Modals\CreateOrUpdateSeo;
use App\Orchid\Traits\ActivitySignsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class SeoScreen extends ListScreenPattern
{
    public string $name = 'Список SEO страниц';

    protected int $paginate = 50;

    use ActivitySignsTrait;

    public function __construct()
    {
        $this->route = OrchidRoutes::SEO;
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить')->icon('plus')->method('save')
                ->modal('createOrUpdateSeoPage')->asyncParameters(),
        ];
    }

    public function query(): iterable
    {
        $this->model = Seo::query()->filters([
            IsActiveFilter::class,
            DateCreatedFilter::class,
        ]);

        return parent::query();
    }

    public function layout(): iterable
    {
        return [
            Layout::table('items', [
                TD::make('id', 'ID')->sort()->filter(TD::FILTER_NUMERIC),
                TD::make('title', 'Название')->sort()->filter(),
                TD::make('url', 'Url')->sort()->filter(),

                TD::make('created_at', 'Дата')->width(150)->alignRight()->sort()
                    ->filter(DateTimer::make()->title('Фильтр по дате')->format('d-m-Y'))
                    ->render(fn($item) => $item->created_at?->format('d-m-Y')),

                TD::make()->width(10)->alignRight()->cantHide()->render(fn($item) => DropDown::make()->icon('options-vertical')->list([
                    ModalToggle::make('Редактировать')->icon('wrench')->method('save')
                        ->modal('createOrUpdateSeoPage')->asyncParameters(['id' => $item->id]),
                    Button::make('Удалить')->icon('trash')->method('deleteItem', ['id' => $item->id, 'title' => $item->getTitle()])
                        ->confirm('Вы действительно хотите удалить запись №' . $item->id . '<br><strong>' . $item->getTitle() . '</strong> <code>(' . $item->url . ')</code>?'),
                ])),
            ]),
            Layout::modal('createOrUpdateSeoPage', CreateOrUpdateSeo::getModal())->title('Добавить SEO')
                ->applyButton('Сохранить')->closeButton('Отменить')->async('asyncGetItem'),
            Layout::modal('deleteItem', EmptyModal::class)->title('Удалить запись?')
                ->applyButton('Да')->closeButton('Нет')->async('asyncGetItem'),
        ];
    }

    public function asyncGetItem(Seo $item)
    {
        return [
            'item' => $item,
        ];
    }

    public function save(Seo $item, Request $request)
    {
        $data = $request->input('item');
        $data['url'] = Str::finish(Str::start($data['url'], '/'), '/');

        $validator = OrchidHelper::getValidator($data, 'seo', 'url');
        if (!$validator->fails()) {
            if ($data['id']) {
                $item = $item->whereId($data['id'])->first();
            }
            $item->fill($data)->save();
            Alert::success('Новый проект успешно добавлен');
        }


        return redirect()->route($this->route->base())->withErrors($validator);
    }

    public function deleteItem(Seo $item)
    {
        $id = $item->id;
        $title = $item->getTitle();
        $item->delete() ? Alert::success("Запись №:$id - '$title'  успешно удалена!")
            : Alert::error("Произошла ошибка при попытке удалить запись");
    }
}

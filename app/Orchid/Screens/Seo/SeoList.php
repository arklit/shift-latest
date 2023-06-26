<?php

namespace App\Orchid\Screens\Seo;

use App\Enums\OrchidRoutes;
use App\Models\Seo;
use App\Orchid\Abstractions\ListScreenPattern;
use App\Orchid\Filters\DateCreatedFilter;
use App\Orchid\Filters\IsActiveFilter;
use App\Orchid\Helpers\OrchidHelper;
use App\Orchid\Layouts\EmptyModal;
use App\Orchid\Traits\ActivitySignsTrait;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class SeoList extends ListScreenPattern
{
    public string $name = 'Список SEO страниц';

    use ActivitySignsTrait;

    public function __construct()
    {
        $this->route = OrchidRoutes::seo;
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
                TD::make('is_active', 'Активность')->sort()->filter(
                    Select::make()->options(OrchidHelper::getYesNoArray())->empty()->title('Фильтр активности')
                )->render(fn($item) => $item->is_active ? $this->activeSign : $this->inactiveSign),
                TD::make('sort', 'Сортировка')->sort()->filter(TD::FILTER_NUMBER_RANGE),
                TD::make('title', 'Название')->sort()->filter(),
                TD::make('url', 'Url')->sort()->filter(),

                TD::make('created_at', 'Дата')->width(100)->alignRight()->sort()
                    ->filter(DateTimer::make()->title('Фильтр по дате')->format('d-m-Y'))
                    ->render(fn($item) => $item->created_at?->format('d-m-Y')),

                TD::make()->width(10)->alignRight()->cantHide()->render(fn($item) => DropDown::make()->icon('options-vertical')->list([
                    Link::make(__('Edit'))->icon('wrench')->route(OrchidRoutes::article->edit(), $item),
                    Button::make('Удалить')->icon('trash')->method('deleteItem', ['id' => $item->id, 'title' => $item->getTitle()])
                        ->confirm('Вы действительно хотите удалить запись №:' . $item->id . ' - ' . $item->getTitle() . '?'),
                ])),
            ]),

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

    public function deleteItem(Seo $item)
    {
        $id = $item->id;
        $title = $item->getTitle();
        $item->delete() ? Alert::success("Запись №:$id - '$title'  успешно удалена!")
            : Alert::error("Произошла ошибка при попытке удалить запись");
    }
}

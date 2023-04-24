<?php

    namespace App\Orchid\Screens\StaticPages;

    use App\Enums\OrchidRoutes;
    use App\Models\Product;
    use App\Models\Project;
    use App\Models\StaticPage;
    use App\Orchid\Filters\DateCreatedFilter;
    use App\Orchid\Filters\IsActiveFilter;
    use App\Orchid\Abstractions\ListScreenPattern;
    use App\Orchid\Helpers\OrchidHelper;
    use App\Orchid\Traits\ActivitySignsTrait;
    use Orchid\Screen\Actions\Button;
    use Orchid\Screen\Actions\DropDown;
    use Orchid\Screen\Actions\Link;
    use Orchid\Screen\Fields\DateTimer;
    use Orchid\Screen\Fields\Input;
    use Orchid\Screen\Fields\Select;
    use Orchid\Screen\TD;
    use Orchid\Support\Facades\Alert;
    use Orchid\Support\Facades\Layout;

    class StaticPageList extends ListScreenPattern
    {
        public string $name                 = 'Список Страниц';

        use  ActivitySignsTrait;

        public function __construct()
        {
            $this->routeName = OrchidRoutes::static->value;
        }

        public function query()
        {
            $this->model = StaticPage::query()->filters([
                IsActiveFilter::class,
                DateCreatedFilter::class,
            ]);
            return parent::query();
        }

        public function layout(): iterable
        {
            return [
                Layout::table('items', [
                    TD::make('id', 'ID')->sort()->filter(TD::FILTER_NUMBER_RANGE),
                    TD::make('is_active', 'Активность')->sort()->filter(
                        Select::make()->options(OrchidHelper::getYesNoArray())->empty()->title('Фильтр активности')
                    )->render(fn($item) => $item->is_active ? $this->activeSign : $this->inactiveSign),
                    TD::make('title', 'Название')->sort()->filter(),
                    TD::make('sort', 'Сортировка')->sort()->filter(TD::FILTER_NUMBER_RANGE),
                    TD::make('code', 'Код')->sort()->filter(),

                    TD::make('created_at', 'Дата')->width(100)->alignRight()->sort()
                        ->filter(DateTimer::make()->title('Фильтр по дате')->format('d-m-Y'))
                        ->render(fn ($item) => $item->created_at?->format('d-m-Y')),

                    TD::make()->width(10)->alignRight()->cantHide()->render(fn ($item) =>
                    DropDown::make()->icon('options-vertical')->list([
                        Link::make(__('Edit'))->icon('wrench')->route(OrchidRoutes::static->edit(), $item),
                        Button::make('Удалить')->icon('trash')->method('deleteItem', ['id' => $item->id, 'title' => $item->getTitle()])
                            ->confirm('Вы действительно хотите удалить запись №:' . $item->id . ' - ' . $item->getTitle() . '?'),
                    ])),
                ]),
                Layout::modal('deleteItem', Layout::rows([]))->title('Удалить запись?')
                    ->applyButton('Да')->closeButton('Нет')->async('asyncGetItem'),
            ];
        }


        public function asyncGetItem(Product $item)
        {
            return [
                'item' => $item,
            ];
        }

        public function deleteItem(Product $item)
        {
            $id = $item->id;
            $title = $item->getTitle();
            $item->delete() ? Alert::success("Запись №:$id - '$title'  успешно удалена!")
                : Alert::error("Произошла ошибка при попытке удалить запись");
        }
    }

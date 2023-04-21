<?php

    namespace App\Orchid\Screens\Seo;

    use App\Enums\OrchidRoutes;
    use App\Models\Seo;
    use App\Orchid\Filters\DateCreatedFilter;
    use App\Orchid\Filters\IsActiveFilter;
    use App\Orchid\RocontModule\Abstraction\ListScreenPattern;
    use App\Orchid\RocontModule\Helpers\OrchidHelper;
    use App\Orchid\RocontModule\Traits\ActivitySignsTrait;
    use Orchid\Screen\Actions\Link;
    use Orchid\Screen\Fields\DateTimer;
    use Orchid\Screen\Fields\Select;
    use Orchid\Screen\TD;
    use Orchid\Support\Facades\Layout;

    class SeoList extends ListScreenPattern
    {
        public string $name                 = 'Список SEO-записей';

        use ActivitySignsTrait;

        public function __construct()
        {
            $this->routeName = OrchidRoutes::seo->value;
        }

        public function query()
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
                    TD::make('id', 'ID')->sort()->filter(TD::FILTER_NUMBER_RANGE),
                    TD::make('is_active', 'Активность')->sort()->filter(
                        Select::make()->options(OrchidHelper::getYesNoArray())->empty()->title('Фильтр активности')
                    )->render(fn($item) => $item->is_active ? $this->activeSign : $this->inactiveSign),
                    TD::make('sort', 'Сортировка')->sort()->filter(TD::FILTER_NUMBER_RANGE),
                    TD::make('title', 'Название')->sort()->filter(),
                    TD::make('url', 'Url')->sort()->filter(),

                    TD::make('created_at', 'Дата')->width(100)->alignRight()->sort()
                        ->filter(DateTimer::make()->title('Фильтр по дате')->format('d-m-Y'))
                        ->render(fn ($item) => $item->created_at?->format('d-m-Y')),
                    TD::make()->width(10)->alignRight()->render(fn ($item) => Link::make()
                        ->icon('wrench')->route('platform.' . $this->routeName . '.edit', $item)),
                ])
            ];
        }
    }

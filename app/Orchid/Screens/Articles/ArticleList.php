<?php

namespace App\Orchid\Screens\Articles;

use App\Enums\OrchidRoutes;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Orchid\Abstractions\ListScreenPattern;
use App\Orchid\Filters\CategoryFilter;
use App\Orchid\Filters\DateCreatedFilter;
use App\Orchid\Filters\IsActiveFilter;
use App\Orchid\Helpers\OrchidHelper;
use App\Orchid\Traits\ActivitySignsTrait;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ArticleList extends ListScreenPattern
{
    public string $name = 'Список статей';

    use  ActivitySignsTrait;

    public function __construct()
    {
        $this->routeName = OrchidRoutes::article->value;
    }

    public function query()
    {
        $this->model = Article::query()->with('category')->filters([
            IsActiveFilter::class,
            DateCreatedFilter::class,
            CategoryFilter::class,
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
                TD::make('slug', 'Код')->sort()->filter(),
                TD::make('category_id', 'Категория')->render(fn($item) => $item->category?->title)
                    ->sort()->filter(Select::make()->fromQuery(ArticleCategory::query()->active()->sorted(), 'title', 'title')
                        ->empty()->title('Фильтр категории')),

                TD::make('created_at', 'Дата')->width(100)->alignRight()->sort()
                    ->filter(DateTimer::make()->title('Фильтр по дате')->format('d-m-Y'))
                    ->render(fn($item) => $item->created_at?->format('d-m-Y')),

                TD::make()->width(10)->alignRight()->cantHide()->render(fn($item) => DropDown::make()->icon('options-vertical')->list([
                    Link::make(__('Edit'))->icon('wrench')->route(OrchidRoutes::article->edit(), $item),
                    Button::make('Удалить')->icon('trash')->method('deleteItem', ['id' => $item->id, 'title' => $item->getTitle()])
                        ->confirm('Вы действительно хотите удалить публикацию №:' . $item->id . ' - ' . $item->getTitle() . '?'),
                ])),
            ]),

            Layout::modal('deleteItem', Layout::rows([]))->title('Удалить статью??')
                ->applyButton('Да')->closeButton('Нет')->async('asyncGetItem'),
        ];
    }

    public function asyncGetItem(Article $item)
    {
        return [
            'item' => $item,
        ];
    }

    public function deleteItem(Article $item)
    {
        $id = $item->id;
        $title = $item->getTitle();
        $item->delete() ? Alert::success("Публикация №:$id - '$title'  успешно удалена!")
            : Alert::error("Произошла ошибка при попытке удалить публикацию");
    }
}

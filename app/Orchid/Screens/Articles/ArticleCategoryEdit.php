<?php

namespace App\Orchid\Screens\Articles;

use App\Enums\OrchidRoutes;
use App\Models\ArticleCategory;
use App\Orchid\Abstractions\EditScreenPattern;
use App\Orchid\Helpers\OrchidHelper;
use App\Orchid\Traits\CommandBarDeletableTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ArticleCategoryEdit extends EditScreenPattern
{
    protected string $createTitle = 'Создание категории статей';
    protected string $updateTitle = 'Редактирование категории статей';
    protected string $deleteMessage = 'Запись успешно удалена';
    protected string $createMessage = 'Запись успешно добавлена';
    protected string $titleColumnName = 'title';

    use CommandBarDeletableTrait;

    public function __construct()
    {
        $this->route = OrchidRoutes::ARTICLE_CATEGORIES;
        $this->routeName = $this->route->list();
    }

    public function query(ArticleCategory $item, $id)
    {
        return [
            'item' => $item->whereId($id)->first(),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                CheckBox::make('item.is_active')->title('Активность')->sendTrueOrFalse()->value(true),
                Input::make('item.sort')->title('Порядок Сортировки')->type('number')->value(0),
                Input::make('item.title')->title('Название')->required()->maxlength(169)->help('Не более 169 символов'),
                Input::make('item.code')->title('Код')->required()->help('Не более 30 символов'),
                TextArea::make('item.description')->title('Описание')->rows(5),
                Input::make('item.seo_title')->title('Title')->maxlength(169)->help('Не более 169 символов'),
                TextArea::make('item.seo_description')->title('Description')->rows(5),
            ]),

        ];
    }

    public function save(ArticleCategory $item, Request $request)
    {
        $data = $request->input('item');
        $data['code'] = Str::slug($data['code']);
        $data['sort'] = $data['sort'] ?? 0;
        $data['id'] = $item->id;

        if ($result = $this->validation($item, $data, 'code')) {
            return $result;
        }

        return $this->saveItem($item, $data);
    }

    public function remove(ArticleCategory $item): RedirectResponse
    {
        if ($item->articles()->count() !== 0) {
            Alert::error('Эта категория не является пустой. Её нельзя удалить');
            return redirect()->route($this->route->edit(), ['id' => $item->id]);
        }

        return $this->removeItem($item);
    }
}

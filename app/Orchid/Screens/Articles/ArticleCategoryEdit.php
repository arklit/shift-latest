<?php

namespace App\Orchid\Screens\Articles;

use App\Enums\OrchidRoutes;
use App\Models\ArticleCategory;
use App\Orchid\Abstractions\EditScreenPattern;
use App\Orchid\Helpers\OrchidHelper;
use App\Orchid\Helpers\OrchidValidator;
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
use function Laravel\Prompts\search;

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

    public function query(ArticleCategory $item, ?int $id = null): array
    {
        return $this->queryMake($item, $id);
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

    public function save(ArticleCategory $item, Request $request, ?int $id = null)
    {
        if ($id){
            $this->id = $id;
            $item = $item->whereId($id)->first();
        }

        $data = $request->input('item');
        $data['code'] = Str::slug($data['code']);
        $data['sort'] = $data['sort'] ?? 0;
        $data['id'] = $id;

        $validator = (new OrchidValidator($data, ['title', 'sort']))->setIndividualRules($this->getRules(), $this->getMessages())
            ->setUniqueFields($item, ['code' => 'Такой код уже используется'])
            ->validate();

        return $validator->isFail() ? $validator->showErrors($this->route, $id) : $this->saveItem($item, $data);
    }

    public function remove(ArticleCategory $item, $id): RedirectResponse
    {
        if ($item->articles()->count() !== 0) {
            Alert::error('Эта категория не является пустой. Её нельзя удалить');
            return redirect()->route($this->route->edit(), ['id' => $id]);
        }

        return $this->removeItem($item, $id);
    }

    public function getRules(): array
    {
        return [
            'code' => ['bail', 'required', 'max:160'],
            'description' => ['bail', 'nullable', 'max:1024'],
            'seo_title' => ['bail', 'nullable', 'max:169'],
            'seo_description' => ['bail', 'nullable'],
        ];
    }

    public function getMessages(): array
    {
        return [
            'code.required' => 'Введите код категории',
            'code.max' => 'Заголовок не может быть длиннее 160 символов',
            'description.required' => 'Введите описание категории',
            'description.max' => 'Описание не может быть длиннее 160 символов',
            'seo_title.required' => 'Введите SEO заголовок',
            'seo_description.required' => 'Введите SEO описание',
        ];
    }
}

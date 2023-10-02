<?php

namespace App\Orchid\Screens\Articles;

use App\Enums\OrchidRoutes;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Orchid\Abstractions\EditScreenPattern;
use App\Orchid\Helpers\OrchidHelper;
use App\Orchid\Helpers\OrchidValidator;
use App\Orchid\Layouts\EmptyModal;
use App\Orchid\Traits\CommandBarDeletableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Orchid\Screen\Fields\CheckBox;
use App\Orchid\Fields\Cropper;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;

class ArticleEdit extends EditScreenPattern
{
    protected string $createTitle = 'Создание статьи';
    protected string $updateTitle = 'Редактирование статьи';
    protected string $deleteMessage = 'Запись успешно удалена';
    protected string $createMessage = 'Запись успешно добавлена';
    protected string $titleColumnName = 'title';

    use CommandBarDeletableTrait;

    public function __construct()
    {
        $this->route = OrchidRoutes::ARTICLES;
    }

    public function layout(): iterable
    {
        return [
            Layout::columns([
                Layout::rows([
                    Group::make([
                        CheckBox::make('item.is_active')->title('Активность')->sendTrueOrFalse()->value(true),
                        Label::make('item.slug')->title('Код')->popover('Генерируется автоматически на основе заголовка'),
                    ]),
                    Input::make('item.title')->title('Заголовок')->required()->maxlength(120)->help('Не более 120 символов'),
                    TextArea::make('item.description')->title('Анонс')->rows(5)->maxlength(1024)->required(),
                    Input::make('item.seo_title')->title('SEO title')->maxlength(160)->help('Заголовок для SEO. Не более 160 символов'),
                    TextArea::make('item.seo_description')->title('SEO description')->maxlength(1024)->rows(5)->help('Описание для SEO. Не более 1024 символов'),
                    Cropper::make('item.image_outer')->title('Изображение для страницы')->targetRelativeUrl()->help('Загрузка изображения обязательна'),
                ]),
                Layout::rows([
                    Select::make('item.category_id')->title('Категория')->empty('Категория не выбрана')
                        ->fromQuery(ArticleCategory::query()->active()->sorted(), 'title', 'id')->required(),
                    DateTimer::make('item.publication_date')->title('Дата публикации')->format24hr()->required()->value(Carbon::today()),
                    Quill::make('item.text')->title('Текст публикации')->required(),
                    Cropper::make('item.image_inner')->title('Изображение для списка')->targetRelativeUrl()->help('Загрузка изображения обязательна'),
                ]),
            ]),
            Layout::modal('deleteArticle', EmptyModal::class)->title('Удалить статью??')
                ->applyButton('Да')->closeButton('Нет')->async('asyncGetArticle'),
        ];
    }

    public function query(Article $item, ?int $id = null)
    {
        return $this->queryMake($item, $id);
    }

    public function save(Article $item, Request $request, ?int $id = null)
    {
        if ($id){
            $this->id = $id;
            $item = $item->whereId($id)->first();
        }

        $data = $request->input('item');

        $validator = (new OrchidValidator($data, []))->setIndividualRules($this->getRules(), $this->getMessages())
            ->clearQuillTags(['text'])
            ->validate();

        if ($item->exists) {
            $data['slug'] = $item->getIdentifier();
        } else {
            $data['slug'] = Str::slug($data['title']);
            $item->fill($data)->save();
            $data['slug'] = Str::slug($item->id . '-' . $data['title']);
        }

        return $validator->isFail() ? $validator->showErrors($this->route, $id) : $this->saveItem($item, $data);
    }

    public function asyncGetArticle(Article $item)
    {
        return [
            'item' => $item,
        ];
    }

    public function remove(Article $item, $id)
    {
        return $this->removeItem($item, $id);
    }

    public function getRules(): array
    {
        return [
            'title' => ['bail', 'required', 'max:120'],
            'category_id' => ['bail', 'required',],
            'description' => ['bail', 'required', 'max:1024'],
            'text' => ['bail', 'required',],
            'image_inner' => ['bail', 'required',],
            'image_outer' => ['bail', 'required',],
            'publication_date' => ['bail', 'required',],
            'seo_description' => ['bail', 'nullable', 'max:1024'],
        ];
    }

    public function getMessages(): array
    {
        return [
            'title.required' => 'Введите заголовок статьи',
            'title.max' => 'Заголовок статьи не может быть длиннее 120 символов',
            'category_id.required' => 'Выберите категорию статьи',
            'description.required' => 'Введите анонс статьи',
            'description.max' => 'Анонс не может быть длиннее 1024 символов',
            'text.required' => 'Введите текст статьи',
            'image_inner.required' => 'Загрузите изображение для страницы',
            'image_outer.required' => 'Загрузите изображение для списка',
            'publication_date.required' => 'Выберите дату публикации',
            'seo_description.max' => 'SEO Description не может быть длиннее 1024 символов',
        ];
    }
}

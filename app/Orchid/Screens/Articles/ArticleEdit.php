<?php

namespace App\Orchid\Screens\Articles;

use App\Enums\OrchidRoutes;
use App\Helpers\CommonHelper;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Orchid\RocontModule\Abstraction\EditScreenPattern;
use App\Orchid\RocontModule\Helpers\OrchidHelper;
use App\Orchid\RocontModule\Traits\CommandBarDeletableTrait;
use App\Services\MakeCodeValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Cropper;
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
    protected string $createTitle = 'Создание Статьи';
    protected string $updateTitle = 'Редактирование Статьи';
    protected string $deleteMessage = 'Запись успешно удалена';
    protected string $createMessage = 'Запись успешно добавлена';
    protected string $titleName = 'title';

    use CommandBarDeletableTrait;

    public function __construct()
    {
        $this->listRedirect = OrchidRoutes::article->list();
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
                    Select::make('item.category_id')->title('Категория')->empty('Категория не выбрана')
                        ->fromQuery(ArticleCategory::query()->active()->sorted(), 'title', 'id')->required(),
                    TextArea::make('item.description')->title('Анонс')->rows(5)->maxlength(1024)->required(),
                    Input::make('item.seo_title')->title('Title ')->required()->maxlength(169)->help('Заголовок для SEO. Не более 169 символов'),
                    TextArea::make('item.seo_description')->title('Description ')->maxlength(1024)->rows(5)->help('Описание для SEO. Не более 1024 символов'),
                    Cropper::make('item.image_outer')->title('Изображение для страницы')->targetRelativeUrl()
//                        ->required()
                    ,

                ]),
                Layout::rows([
                    Input::make('item.title')->title('Заголовок')->required()->maxlength(169)->help('Не более 169 символов'),
                    DateTimer::make('item.publication_date')->title('Дата публикации')->format24hr()->required(),
                    Quill::make('item.text')->title('Текст публикации')->required(),

                    Cropper::make('item.image_inner')->title('Изображение для списка')->targetRelativeUrl()
//                        ->required()
                    ,
                ]),

                Layout::modal('deleteArticle', Layout::rows([]))->title('Удалить статью??')
                    ->applyButton('Да')->closeButton('Нет')->async('asyncGetArticle'),
            ]),
        ];
    }

    public function query(Article $item)
    {
        return $this->queryMake($item);
    }

    public function save(Article $item, Request $request)
    {
        $data = $request->input('item');
        $data['slug'] = Str::slug($data['title']);

        $presets = OrchidHelper::getPreset('validators', OrchidRoutes::article->value);
        $presets['rules']['slug'] = [Rule::unique($item->getTable(), 'slug')->ignore($item->id)];
        $presets['messages']['slug.unique'] = 'Такой заголовок уже используется';
        $result = OrchidHelper::validate($item, OrchidRoutes::article, $data, $presets);

        if (!is_null($result)) {
            return $result;
        }

        return $this->saveItem($item, $data);
    }

    public function asyncGetArticle(Article $item)
    {
        return [
            'item' => $item,
        ];
    }

    public function remove(Article $item)
    {
        return $this->removeItem($item);
    }
}

<?php

namespace App\Orchid\Screens\Pages;

use App\Models\InformationPage;
use App\Orchid\Abstractions\EditScreenPattern;
use App\Orchid\Fields\TinyMce;
use App\Orchid\Traits\CommandBarDeletableTrait;
use App\Services\GetUriService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;

class InformationPageEdit extends EditScreenPattern
{
    use CommandBarDeletableTrait;

    protected ?string $listRedirect = 'platform.information.list';
    protected string $createTitle = 'Создание статичной страницы';
    protected string $updateTitle = 'Редактирование статичной страницы';
    protected string $deleteMessage = 'Запись успешно удалена';
    protected string $createMessage = 'Запись успешно добавлена';
    protected string $titleName = 'title';
    protected array $page_type = ['Страница', 'page'];
    protected array $relations = ['products'];

    public function __construct()
    {
        $this->getUriService = new GetUriService();
    }
    public function layout(): iterable
    {
        return [
            Layout::columns([
                Layout::rows([
                    CheckBox::make('item.is_active')->title('Активность')->sendTrueOrFalse()->value(true),
                    Label::make('item.page_type')->title('Тип страницы')->value($this->page_type[0])->style('margin-bottom:0'),
                    Input::make('item.title')->title('Заголовок страницы')->required(),
                    Input::make('item.code')->title('Код страницы')->required(),
                    Select::make('item.parent_id')->title('Родитель')
                        ->empty('Выберите родителя')
                        ->fromQuery(InformationPage::query()->active(), 'title', 'id'),
                ]),
                Layout::rows([
                    TextArea::make('item.announce')->title('Анонс')->rows(5)->required(),
                    Cropper::make('item.image_outer')->targetRelativeUrl()->title('Изображение в списке'),
                    Cropper::make('item.image_inner')->targetRelativeUrl()->title('Изображение в карточке'),
                ])
            ]),
            Layout::columns([
                Layout::rows([
                    TinyMce::make('item.text')->title('Текст')->required(),
                    Input::make('item.type')->value($this->page_type[1])->hidden()->class(),
                ])
            ])
        ];
    }

    public function query(InformationPage $item)
    {
        return $this->queryMake($item);
    }

    public function save(InformationPage $item, Request $request)
    {
        $this->itemID = $item->id;
        $data = $request->input('item');
        $data['uri'] = $this->getUriService->getUri($data);

        $validator = Validator::make($data, $this->getRules(), $this->getMessages());

        if ($validator->fails()) {
            return redirect()->route('platform.information-page.edit', [$item->id])->withErrors($validator)->withInput();
        }

        return $this->saveItem($item, $data);
    }

    public function remove(InformationPage $item)
    {
        return $this->removeItem($item);
    }

    protected function getRules(): array
    {
        return [
            'code'        => ['bail', 'required', 'regex:~^[A-Za-z0-9\-_]+$~', Rule::unique(InformationPage::TABLE_NAME)->ignore($this->itemID)],
        ];
    }

    protected function getMessages(): array
    {
        return [
            'code.required'        => 'Укажите код брэнда',
            'code.regex'           => 'В коде допустимы только цифры и латинские буквы',
            'code.unique'          => 'Такой код уже используется',
        ];
    }
}

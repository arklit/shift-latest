<?php

namespace App\Orchid\Screens\StaticPages;

use App\Enums\OrchidRoutes;
use App\Models\StaticPage;
use App\Orchid\Abstractions\EditScreenPattern;
use App\Orchid\Helpers\OrchidValidator;
use App\Orchid\Traits\CommandBarDeletableTrait;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Layout;

class StaticPageEdit extends EditScreenPattern
{
    protected string $createTitle = 'Создание страницы';
    protected string $updateTitle = 'Редактирование страницы';
    protected string $deleteMessage = 'Запись успешно удалена';
    protected string $createMessage = 'Запись успешно добавлена';
    protected string $titleColumnName = 'title';
    protected StaticPage $item;

    use CommandBarDeletableTrait;

    public function __construct()
    {
        $this->route = OrchidRoutes::STATIC_PAGES;
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Group::make([
                    CheckBox::make('item.is_active')->title('Активность')->sendTrueOrFalse()->value(true),
                    CheckBox::make('item.indexation')->title('Открыть страницу для индексирования')->sendTrueOrFalse()->value(false),
                    Select::make('item.parent_id')->title('Выбрать родителя')->empty('Родитель отсутствует')->value(0)
                        ->fromQuery(StaticPage::query()->active()->sorted()->where('id', '!=', $this->item->id), 'title', 'id'),
                    Input::make('item.sort')->title('Порядок сортировки')->type('number')->value(0),
                ]),
                Group::make([
                    Input::make('item.title')->title('Название')->required()->maxlength(169)->help('Не более 169 символов'),
                    Input::make('item.code')->title('Код')->required()->maxlength(30)->help('Не более 30 символов'),
                ]),
                TextArea::make('item.description')->title('Описание')->rows(5)->required(),
                Upload::make('item.documents')->groups($this->route->value)->title('Документы'),
            ]),
        ];
    }

    public function query(StaticPage $item)
    {
        $this->item = $item;
        return $this->queryMake($item);
    }

    public function save(StaticPage $item, Request $request)
    {
        $data = $request->input('item');
//        $payload = $data['data'];
        $code = $data['code'];
        $rules = $this->getRules($code);
        $messages = $this->getMessages($code);

        $validator = (new OrchidValidator($data))->setIndividualRules($rules, $messages);

        if (StaticPage::CODE_ABOUT === $code) {
            $validator->clearQuillTags(['description_text'])->validate();
            return $validator->isFail() ? $validator->showErrors($this->route, $item->id) : $this->saveItem($item, $data);
        }
//        if (StaticPage::CODE_DELIVERY === $code) {
//            $validator->clearQuillTags(['rules'])->validate();
//            $repeaterErrors = [];
//            foreach ($payload['advantages'] as $block) {
//                if (empty($block['icon'])) {
//                    $repeaterErrors['icon'] = 'Загрузите иконку для транспортной компании';
//                }
//            }
//            $errors = array_merge($validator->getErrorsAsArray(), $repeaterErrors);
//            return empty($errors) ? $this->saveItem($item, $data) : redirect()->route($this->route->edit(), [$item->id])->withErrors($errors)->withInput();
//        }
        if (StaticPage::CODE_CONTACTS === $code) {
            return $validator->isFail() ? $validator->showErrors($this->route, $item->id) : $this->saveItem($item, $data);
        }
        if (StaticPage::CODE_COOPERATION === $code) {
            $validator->clearQuillTags(['conditions'])->validate();
            return $validator->isFail() ? $validator->showErrors($this->route, $item->id) : $this->saveItem($item, $data);
        }

        return $this->saveItem($item, $data);
    }

    public function remove(StaticPage $item)
    {
        return $this->removeItem($item);
    }

    public function getRules(string $code): array
    {
        $base = ['bail', 'required'];
        return match ($code) {
            StaticPage::CODE_ABOUT => [
                'title'             => $base,
                'description_title' => $base,
                'description_text'  => $base,
                'image_main'        => $base,
                'certificates'      => $base,
            ],
            StaticPage::CODE_DELIVERY => [
                'rules' => $base,
            ],
            StaticPage::CODE_CONTACTS => [
                'certificates' => $base,
            ],
            StaticPage::CODE_COOPERATION => [
                'conditions' => $base,
            ],
            default => [],
        };
    }

    public function getMessages(string $code): array
    {
        return match ($code) {
            StaticPage::CODE_ABOUT => [
                'title.required'             => 'Введите название страницы',
                'description_title.required' => 'Введите заголовок в описании',
                'description_text.required'  => 'Введите текст в описании',
                'image_main.required'        => 'Загрузите заглавное изображение',
                'certificates.required'      => 'Загрузите хотя бы один сертификат',
            ],
            StaticPage::CODE_DELIVERY => [
                'rules.required' => 'Укажите правила доставки',
            ],
            StaticPage::CODE_CONTACTS => [
                'certificates.required' => 'Загрузите файл с реквизитами',
            ],
            StaticPage::CODE_COOPERATION => [
                'conditions.required' => 'Укажите условия сотрудничества',
            ],
            default => [],
        };
    }
}

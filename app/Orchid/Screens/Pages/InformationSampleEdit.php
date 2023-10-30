<?php

namespace App\Orchid\Screens\Pages;

use App\Enums\OrchidRoutes;
use App\Enums\StaticPages;
use App\Models\InformationPage;
use App\Orchid\Abstractions\EditScreenPattern;
use App\Orchid\Helpers\OrchidValidator;
use App\Orchid\Layouts\StaticPages\AboutCompanyPageLayout;
use App\Orchid\Layouts\StaticPages\ContactsPageLayout;
use App\Orchid\Layouts\StaticPages\CooperationPageLayout;
use App\Orchid\Layouts\StaticPages\DeliveryPageLayout;
use App\Orchid\Traits\CommandBarUndelitableTrait;
use Illuminate\Http\Request;
use Orchid\Screen\Layouts\Rows;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InformationSampleEdit extends EditScreenPattern
{
    protected ?string $listRedirect = 'information.page.list';
    protected string $createTitle = 'Создание страницы по шаблону';
    protected string $updateTitle = 'Редактирование страницы по шаблону';
    protected string $deleteMessage = 'Запись успешно удалена';
    protected string $createMessage = 'Запись успешно добавлена';
    protected string $titleName = 'title';
    protected ?Rows $layout = null;

    use CommandBarUndelitableTrait;

    public function __construct()
    {
        $this->route = OrchidRoutes::INFO_PAGES;
    }

    public function query(InformationPage $item, Request $request)
    {
        $this->layout = match ($request->input('template')) {
            StaticPages::PAGE_ABOUT->value => new AboutCompanyPageLayout(),
            StaticPages::PAGE_CONTACTS->value => new ContactsPageLayout(),
            StaticPages::PAGE_COOPERATION->value => new CooperationPageLayout(),
            default => throw new HttpException(404, 'Статическая страница не найдена'),
        };

        $this->updateTitle .= '"' . $item->getTitle() . '"';

        return $this->queryMake($item);
    }

    public function layout(): iterable
    {
        return [
            $this->layout,
        ];
    }

    public function save(InformationPage $item, Request $request)
    {
        $data = $request->input('item');
        $payload = $data['data'] ?? [];
        $code = $item->getCode();
        $rules = $this->getRules($code);
        $messages = $this->getMessages($code);

        $validator = (new OrchidValidator($payload))->setIndividualRules($rules, $messages);

        if (StaticPages::PAGE_ABOUT === $code) {
            $validator->clearQuillTags(['description_text'])->validate();
            return $validator->isFail() ? $validator->showErrors($this->route, $item->id) : $this->saveItem($item, $data);
        }

        if (StaticPages::PAGE_CONTACTS === $code) {
            return $validator->validate()->isFail() ? $validator->showErrors($this->route, $item->id) : $this->saveItem($item, $data);
        }
        if (StaticPages::PAGE_COOPERATION === $code) {
            $validator->clearQuillTags(['conditions'])->validate();
            return $validator->isFail() ? $validator->showErrors($this->route, $item->id) : $this->saveItem($item, $data);
        }

        return $this->saveItem($item, $data);
    }

    public function remove(InformationPage $item)
    {
        return $this->removeItem($item);
    }

    public function getRules(string $code): array
    {
        $base = ['bail', 'required'];
        return match ($code) {
            StaticPages::PAGE_ABOUT => [
                'title'             => $base,
                'description_title' => $base,
                'description_text'  => $base,
                'image_main'        => $base,
                'certificates'      => $base,
            ],
            StaticPages::PAGE_CONTACTS => [
                'certificates' => $base,
            ],
            StaticPages::PAGE_COOPERATION => [
                'conditions' => $base,
            ],
            default => [],
        };
    }

    public function getMessages(string $code): array
    {
        return match ($code) {
            StaticPages::PAGE_ABOUT => [
                'title.required'             => 'Введите название страницы',
                'description_title.required' => 'Введите заголовок в описании',
                'description_text.required'  => 'Введите текст в описании',
                'image_main.required'        => 'Загрузите заглавное изображение',
                'certificates.required'      => 'Загрузите хотя бы один сертификат',
            ],
            StaticPages::PAGE_CONTACTS => [
                'certificates.required' => 'Загрузите файл с реквизитами',
            ],
            StaticPages::PAGE_COOPERATION => [
                'conditions.required' => 'Укажите условия сотрудничества',
            ],
            default => [],
        };
    }
}

<?php

namespace App\Orchid\Screens\Seo;

use App\Enums\OrchidRoutes;
use App\Orchid\Abstractions\EditScreenPattern;
use App\Services\SitemapGenerator;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Code;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class SitemapScreen extends EditScreenPattern
{
    protected ?string $listRedirect = 'platform.main';
    protected string $createTitle = 'Создание Карты';
    protected string $updateTitle = 'Редактирование ITEM';
    protected string $deleteMessage = 'Запись успешно удалена';
    protected string $createMessage = 'Запись успешно добавлена';
    public string $name = 'Карта сайта';

    public function commandBar(): array
    {
        return [
            Button::make('Обновить')->icon('refresh')->type(Color::INFO())->method('generateXml'),
            Link::make(__('orchid.go-back'))->icon('arrow-left-circle')->route('platform.main'),
        ];
    }

    public function generateXml()
    {
        $generator = new SitemapGenerator();
        $mapContent = $generator->generateMap();
        Storage::disk('public')->put('/sitemap.xml', $mapContent);
        Alert::success('Карта сайта успешно обновлена!');
//        return redirect()->route(OrchidRoutes::sitemap->base());
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Code::make('xml')->language(Code::MARKUP)->readonly()->height('1000px'),
            ]),
            Layout::modal('generateXml', Layout::rows([]))->title('Обновление sitemap может быть нагруженной операцией. Не выполняйте её без необходимости')
                ->applyButton('Выполнить обновление')->closeButton('Отменить'),
        ];
    }

    public function query()
    {
        $xml = Storage::disk('public')->get('/sitemap.xml') ?? '';

        return [
            'xml' => $xml,
        ];
    }
}

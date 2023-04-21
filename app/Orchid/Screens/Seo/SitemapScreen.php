<?php

    namespace App\Orchid\Screens\Seo;

    use App\Orchid\RocontModule\Abstraction\EditScreenPattern;
    use Orchid\Screen\Actions\Button;
    use Orchid\Screen\Actions\Link;
    use Orchid\Screen\Fields\Code;
    use Orchid\Support\Color;
    use Orchid\Support\Facades\Alert;
    use Orchid\Support\Facades\Layout;

    class SitemapScreen extends EditScreenPattern
    {
        protected ?string $listRedirect    = 'platform.main';
        protected string $createTitle      = 'Создание Карты';
        protected string $updateTitle      = 'Редактирование ITEM';
        protected string $deleteMessage    = 'Запись успешно удалена';
        protected string $createMessage    = 'Запись успешно добавлена';
        public string $name                = 'Карта сайта';

        public function commandBar(): array
        {
            return [
                Button::make('Обновить')->icon('refresh')->type(Color::INFO())->method('generateXml'),
                Link::make(__('orchid.go-back'))->icon('arrow-left-circle')->route('platform.main'),
            ];
        }

        public function generateXml()
        {
            Alert::success('Карта сайта успешно обновлена!');
            return redirect()->route('platform.sitemap');
        }

        public function layout(): iterable
        {
            return [
                Layout::rows([
                    Code::make('xml')->language(Code::MARKUP)->readonly()->height('1000px'),
                ]),
            ];
        }

        public function query()
        {
            $url = !app()->isProduction() ? 'https://nartis.rocont.ru/sitemap.xml' : route('web.xml-map');
            $xml = file_get_contents($url);

            return [
                'xml' => $xml,
            ];
        }
    }

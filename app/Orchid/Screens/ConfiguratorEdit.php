<?php

    namespace App\Orchid\Screens;

    use App\Models\Configurator;
    use App\Orchid\Abstractions\EditScreenPattern;
    use Illuminate\Http\Request;
    use Orchid\Screen\Actions\Button;
    use Orchid\Screen\Actions\Link;
    use Orchid\Screen\Fields\Input;
    use Orchid\Screen\Fields\Picture;
    use Orchid\Screen\Fields\TextArea;
    use Orchid\Screen\Fields\Upload;
    use Orchid\Support\Facades\Layout;

    class ConfiguratorEdit extends EditScreenPattern
    {
        protected ?string $listRedirect    = 'platform.configurator.edit';
        protected string $createTitle      = 'Создание конфигурации';
        protected string $updateTitle      = 'Редактирование конфигурации';
        protected string $deleteMessage    = 'Запись успешно удалена';
        protected string $createMessage    = 'Запись успешно добавлена';
        protected array $redirectParams    = ['id' => 1];
        protected bool $redirectAfterUpdate = false;

        public function commandBar()
        {
            return [
                Link::make(__('orchid.go-back'))->icon('arrow-left-circle')->route('platform.main'),
                Button::make(__('orchid.save'))->icon('note')->method('save'),
            ];
        }

        public function layout(): array
        {
            return [
                Layout::columns([
                    Layout::rows([
                        Input::make('item.data.phone')->title('Телефон')->mask('+7 (999) 999-99-99')->required(),
                        Input::make('item.data.address')->title('Адрес')->required(),
                        Input::make('item.data.email')->title('Email')->type('email')->required(),
                        TextArea::make('item.data.coordinates')->title('Координаты'),
                        TextArea::make('item.data.schedule')->title('График работы'),
                        Upload::make('item.attachment')->maxFiles(1)->groups('configurator')->title('Реквизиты (документы)')
                    ]),
                    Layout::rows([
                        TextArea::make('item.data.requisites_text')->rows(5)->title('Реквизиты (текст)'),
                        Picture::make('item.logo')->targetRelativeUrl()->title('Логотип'),
                    ]),
                ]),
            ];
        }

        public function query(Configurator $item)
        {
//            abort_if(empty($item->id), 404);

            return $this->queryMake($item);
        }

        public function save(Configurator $item, Request $request)
        {
            $data = $request->input('item.data');
            $fields = ['phone', 'address', 'email', 'coordinates', 'schedule', 'requisites_text'];

            foreach ($fields as $field) {
                $data[$field] = $data[$field] ?? '';
            }

            $item->data = $data;
            $redirector = $this->saveItem($item, $data);

            $item->attachment()->syncWithoutDetaching(
                $request->input('item.attachment', [])
            );

            return $redirector;
        }

        public function remove(Configurator $item)
        {
            return $this->removeItem($item);
        }
    }

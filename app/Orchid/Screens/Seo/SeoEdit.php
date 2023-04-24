<?php

    namespace App\Orchid\Screens\Seo;

    use App\Enums\OrchidRoutes;
    use App\Models\Seo;
    use App\Orchid\Abstractions\EditScreenPattern;
    use App\Orchid\Traits\CommandBarDeletableTrait;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Orchid\Screen\Fields\CheckBox;
    use Orchid\Screen\Fields\Input;
    use Orchid\Screen\Fields\TextArea;
    use Orchid\Support\Facades\Layout;

    class SeoEdit extends EditScreenPattern
    {
        protected string $createTitle      = 'Создание SEO страницы';
        protected string $updateTitle      = 'Редактирование SEO страницы';
        protected string $deleteMessage    = 'Запись успешно удалена';
        protected string $createMessage    = 'Запись успешно добавлена';
        protected string $titleName        = 'title';

        use CommandBarDeletableTrait;

        public function __construct()
        {
            $this->route = OrchidRoutes::seo;
//            $this->routeName = $this->route->value;
        }

        public function layout(): iterable
        {
            return [
                Layout::rows([
                    CheckBox::make('item.is_active')->title('Активность')->sendTrueOrFalse()->value(true),
                    Input::make('item.url')->title('Код')->required()->maxlength(30)->help('Не более 30 символов'),
                    Input::make('item.sort')->title('Порядок Сортировки')->type('number')->value(0),
                    Input::make('item.title')->title('Title ')->required()->maxlength(169)->help('Не более 169 символов'),
                    TextArea::make('item.description')->title('Описание')->rows(5)->required(),
                ]),
            ];
        }

        public function query(Seo $item)
        {
            return $this->queryMake($item, $this->route);
        }

        public function save(Seo $item, Request $request)
        {
            $data = $request->input('item');
            $data['url'] = Str::finish(Str::start($data['url'], '/'), '/');
            $validator = MakeCodeValidator::handle($item, $data, 'url', 'URL', '~[A-Za-z0-9-_/]*~');

            if ($validator->fails()) {
                return ($item->exists)
                    ? redirect(route(OrchidRoutes::seo->edit(), ['id' => $item->id]))->withErrors($validator)->withInput()
                    : redirect(route(OrchidRoutes::seo->create()))->withErrors($validator)->withInput();
            }

            return $this->saveItem($item, $data);
        }

        public function remove(Seo $item)
        {
            return $this->removeItem($item);
        }
    }

<?php

    namespace App\Orchid\Screens\Seo;

    use App\Enums\OrchidRoutes;
    use App\Models\Seo;
    use App\Orchid\Abstractions\EditScreenPattern;
    use App\Orchid\Helpers\OrchidHelper;
    use App\Orchid\Traits\CommandBarDeletableTrait;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Orchid\Screen\Fields\CheckBox;
    use Orchid\Screen\Fields\Input;
    use Orchid\Screen\Fields\TextArea;
    use Orchid\Support\Facades\Layout;

    class SeoEdit extends EditScreenPattern
    {
        protected string $createTitle = 'Создание SEO страницы';
        protected string $updateTitle = 'Редактирование SEO страницы';
        protected string $deleteMessage = 'Запись успешно удалена';
        protected string $createMessage = 'Запись успешно добавлена';
        protected string $titleColumnName = 'title';

        use CommandBarDeletableTrait;

        public function __construct()
        {
            $this->route = OrchidRoutes::seo;
        }

        public function layout(): iterable
        {
            return [
                Layout::rows([
                    CheckBox::make('item.is_active')->title('Активность')->sendTrueOrFalse()->value(true),
                    Input::make('item.url')->title('URL')->required()->maxlength(60)->help('Не более 60 символов'),
                    Input::make('item.sort')->title('Порядок Сортировки')->type('number')->value(0),
                    Input::make('item.title')->title('Title ')->required()->maxlength(160)->help('Не более 160 символов'),
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

            $presets = OrchidHelper::getValidationStructure($this->route->value);
            $presets = OrchidHelper::setUniqueRule($presets, $item, 'url', 'url', 'URL');
            $result = OrchidHelper::validate($item, $this->route, $data, $presets);

            if (!is_null($result)) {
                return $result;
            }

            return $this->saveItem($item, $data);
        }

        public function remove(Seo $item)
        {
            return $this->removeItem($item);
        }
    }

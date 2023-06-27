<?php

    namespace App\Orchid\Screens\StaticPages;

    use App\Enums\OrchidRoutes;
    use App\Models\StaticPage;
    use App\Orchid\Abstractions\EditScreenPattern;
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
        protected string $createTitle = 'Создание Страницы';
        protected string $updateTitle = 'Редактирование Страницы';
        protected string $deleteMessage = 'Запись успешно удалена';
        protected string $createMessage = 'Запись успешно добавлена';
        protected string $titleColumnName = 'title';
        protected StaticPage $item;

        use CommandBarDeletableTrait;

        public function __construct()
        {
            $this->route = OrchidRoutes::static;
        }

        public function layout(): iterable
        {
            return [
                Layout::rows([
                    Group::make([
                        CheckBox::make('item.is_active')->title('Активность')->sendTrueOrFalse()->value(true),
                        CheckBox::make('item.indexation')->title('Индексация')->sendTrueOrFalse()->value(false),
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
            return $this->saveItem($item, $data);
        }

        public function remove(StaticPage $item)
        {
            return $this->removeItem($item);
        }
    }

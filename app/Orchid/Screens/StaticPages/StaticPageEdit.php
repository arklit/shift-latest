<?php

    namespace App\Orchid\Screens\StaticPages;

    use App\Enums\OrchidRoutes;
    use App\Models\StaticPage;
    use App\Orchid\RocontModule\Abstraction\EditScreenPattern;
    use App\Orchid\RocontModule\Traits\CommandBarDeletableTrait;
    use Illuminate\Http\Request;
    use Orchid\Screen\Fields\CheckBox;
    use Orchid\Screen\Fields\Input;
    use Orchid\Screen\Fields\TextArea;
    use Orchid\Screen\Fields\Upload;
    use Orchid\Support\Facades\Layout;

    class StaticPageEdit extends EditScreenPattern
    {
        protected string $createTitle      = 'Создание Страницы';
        protected string $updateTitle      = 'Редактирование Страницы';
        protected string $deleteMessage    = 'Запись успешно удалена';
        protected string $createMessage    = 'Запись успешно добавлена';
        protected string $titleName        = 'title';

        use CommandBarDeletableTrait;

        public function __construct()
        {
            $this->listRedirect = OrchidRoutes::static->list();
        }

        public function layout(): iterable
        {
            return [
                Layout::rows([
                    CheckBox::make('item.is_active')->title('Активность')->sendTrueOrFalse()->value(true),
                    CheckBox::make('item.indexation')->title('Индексация')->sendTrueOrFalse()->value(false),
                    Input::make('item.title')->title('Название')->required()->maxlength(169)->help('Не более 169 символов'),
                    Input::make('item.code')->title('Код')->required()->maxlength(30)->help('Не более 30 символов'),
                    TextArea::make('item.description')->title('Описание')->rows(5)->required(),
                    Input::make('item.sort')->title('Порядок сортировки')->type('number')->value(0),
                    Upload::make('item.documents')->groups(OrchidRoutes::static->value)->title('Документы'),
                ]),
            ];
        }

        public function query(StaticPage $item)
        {
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

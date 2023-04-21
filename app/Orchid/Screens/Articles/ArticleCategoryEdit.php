<?php

    namespace App\Orchid\Screens\Articles;

    use App\Enums\OrchidRoutes;
    use App\Models\ArticleCategory;
    use App\Orchid\RocontModule\Abstraction\EditScreenPattern;
    use App\Orchid\RocontModule\Traits\CommandBarDeletableTrait;
    use App\Services\MakeCodeValidator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Orchid\Screen\Fields\CheckBox;
    use Orchid\Screen\Fields\Input;
    use Orchid\Screen\Fields\TextArea;
    use Orchid\Support\Facades\Alert;
    use Orchid\Support\Facades\Layout;

    class ArticleCategoryEdit extends EditScreenPattern
    {
        protected string $createTitle      = 'Создание Категории Статей';
        protected string $updateTitle      = 'Редактирование Категории Публикации';
        protected string $deleteMessage    = 'Запись успешно удалена';
        protected string $createMessage    = 'Запись успешно добавлена';
        protected string $titleName        = 'title';

        use CommandBarDeletableTrait;

        public function __construct()
        {
            $this->routeName = OrchidRoutes::art_cat->list();
        }

        public function layout(): iterable
        {
            return [
                Layout::rows([
                    CheckBox::make('item.is_active')->title('Активность')->sendTrueOrFalse()->value(true),
                    Input::make('item.sort')->title('Порядок Сортировки')->type('number')->value(0),
                    Input::make('item.title')->title('Название')->required()->maxlength(169)->help('Не более 169 символов'),
                    Input::make('item.code')->title('Код')->required()->maxlength(30)->help('Не более 30 символов'),
                    TextArea::make('item.description')->title('Описание')->rows(5)->required(),
                    Input::make('item.seo_title')->title('Title ')->required()->maxlength(169)->help('Не более 169 символов'),
                    TextArea::make('item.seo_description')->title('Description ')->rows(5),
                ]),
            ];
        }

        public function query(ArticleCategory $item)
        {
            return $this->queryMake($item);
        }

        public function save(ArticleCategory $item, Request $request)
        {
            $data = $request->input('item');
            $data['code'] = Str::slug($data['code']);
            $data['sort'] = $data['sort'] ?? 0;
            $validator = MakeCodeValidator::handle($item, $data, 'code', 'код');
            $arguments = ($item->exists) ? ['id' => $item->id] : [];
            $route = ($item->exists) ? OrchidRoutes::art_cat->edit() : OrchidRoutes::art_cat->create();

            if ($validator->fails()) {
                return redirect()->route($route, $arguments)->withErrors($validator)->withInput();
            }

            return $this->saveItem($item, $data);
        }

        public function remove(ArticleCategory $item)
        {
            if ($item->articles()->count() !== 0) {
                Alert::error('Эта категория не является пустой. Её нельзя удалить');
                return redirect()->route(OrchidRoutes::art_cat->edit(), ['id' => $item->id]);
            }

            return $this->removeItem($item);
        }
    }

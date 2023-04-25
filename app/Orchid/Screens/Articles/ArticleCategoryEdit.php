<?php

    namespace App\Orchid\Screens\Articles;

    use App\Enums\OrchidRoutes;
    use App\Models\ArticleCategory;
    use App\Orchid\Abstractions\EditScreenPattern;
    use App\Orchid\Helpers\OrchidHelper;
    use App\Orchid\Traits\CommandBarDeletableTrait;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Validation\Rule;
    use Orchid\Screen\Fields\CheckBox;
    use Orchid\Screen\Fields\Input;
    use Orchid\Screen\Fields\TextArea;
    use Orchid\Support\Facades\Alert;
    use Orchid\Support\Facades\Layout;

    class ArticleCategoryEdit extends EditScreenPattern
    {
        protected string $createTitle      = 'Создание Категории Статей';
        protected string $updateTitle      = 'Редактирование Категории Статей';
        protected string $deleteMessage    = 'Запись успешно удалена';
        protected string $createMessage    = 'Запись успешно добавлена';
        protected string $titleName        = 'title';

        use CommandBarDeletableTrait;

        public function __construct()
        {
            $this->route = OrchidRoutes::art_cat;
            $this->routeName = $this->route->list();
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

            $presets = OrchidHelper::getPreset('validators',$this->route->value);
            $presets['rules']['code'][] = Rule::unique($item->getTable(), 'code')->ignore($item->id);
            $presets['messages']['code.unique'] = 'Такой код уже используется';

//            dd($data, $presets);
            $result = OrchidHelper::validate($item, $this->route, $data, $presets);

            if (!is_null($result)) {
                return $result;
            }

            return $this->saveItem($item, $data);
        }

        public function remove(ArticleCategory $item)
        {
            if ($item->articles()->count() !== 0) {
                Alert::error('Эта категория не является пустой. Её нельзя удалить');
                return redirect()->route($this->route->edit(), ['id' => $item->id]);
            }

            return $this->removeItem($item);
        }
    }

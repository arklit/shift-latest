<?php

    namespace App\Orchid\Screens\Pages;

    use App\Models\InformationPage;
    use App\Orchid\Abstractions\ListScreenPattern;
    use App\Orchid\Helpers\OrchidHelper;
    use App\Orchid\Layouts\SelectListener;
    use App\Orchid\Traits\ActivitySignsTrait;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Validation\Rule;
    use Orchid\Screen\Actions\Link;
    use Orchid\Screen\Actions\ModalToggle;
    use Orchid\Screen\Fields\DateTimer;
    use Orchid\Screen\Fields\Select;
    use Orchid\Screen\TD;
    use Orchid\Support\Facades\Layout;

    class InformationPageList extends ListScreenPattern
    {
        protected ?string $listRedirect = 'platform.information.list';
        protected ?string $updateRoute = 'platform.information-link.edit';
        public string $name = 'Список информационных страниц';

        use ActivitySignsTrait;

        public function query(): iterable
        {
            $this->model = InformationPage::query();
            return parent::query();
        }

        public function commandBar(): iterable
        {
            return [
                ModalToggle::make('Создать')->modal('choosePageType')
                    ->icon('plus')
                    ->modalTitle('Выберите тип страницы')
                    ->asyncParameters()
                    ->method('chosePageType')
            ];
        }

        public function layout(): iterable
        {
            return [
                Layout::table('items', [
                    TD::make('id', 'ID'),
                    TD::make('is_active', 'Активность')->sort()->filter(
                        Select::make()->options(OrchidHelper::getYesNoArray())->empty()->title('Фильтр активности')
                    )->render(fn($item) => $item->is_active ? $this->activeSign : $this->inactiveSign),
                    TD::make('type', 'Тип')->sort()->render(fn($item) => $item->type === 'link' ? 'Ссылка' : 'Страница'),
                    TD::make('parent_id', 'Родитель')->render(fn($item) => $item->getParentCode($item))->sort()->filter(Select::make()->fromModel(InformationPage::class, 'code', 'id'))
                        ->filterValue(fn($item) => InformationPage::find($item)->code),
                    TD::make('uri', 'URI')->sort()->filter()->sort()
                        ->filter(),
                    TD::make('created_at', 'Дата')->width(100)->alignRight()->sort()
                        ->filter(DateTimer::make()->title('Фильтр по дате')->format('d-m-Y'))
                        ->render(fn ($item) => $item->created_at?->format('d-m-Y')),
                    TD::make()->width(10)->alignRight()->cantHide()
                        ->render(function ($item) {
                            return Link::make()->icon('wrench')->route($item->getUpdateRoute($item->type), $item);
                        }),
                ]),

                Layout::modal('choosePageType', [SelectListener::class])->async('asyncType'),
            ];
        }

        public function asyncType(string $type = null)
        {
            return [
                'type' => $type,
            ];
        }

        public function chosePageType(InformationPage $item, Request $request): RedirectResponse
        {
            $data = $request->input('item');
            $route = $item->getUpdateRoute($data['type']);

            $validator = Validator::make($data, $this->getRules(), $this->getMessages());

            if ($validator->fails()) {
                return redirect()->route('platform.information.list', [$item->id])->withErrors($validator);
            }

            return redirect()->route($route, $data)->withInput();
        }

        protected function getRules(): array
        {
            return [
                'code'        => ['bail', 'required', 'regex:~^[A-Za-z0-9\-_]+$~', Rule::unique(InformationPage::TABLE_NAME)],
            ];
        }

        protected function getMessages(): array
        {
            return [
                'code.required'        => 'Укажите код брэнда',
                'code.regex'           => 'В коде допустимы только цифры и латинские буквы',
                'code.unique'          => 'Такой код уже используется',
            ];
        }
    }

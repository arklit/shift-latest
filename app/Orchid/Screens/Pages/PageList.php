<?php

namespace App\Orchid\Screens\Pages;

use App\Enums\PagesTypes;
use App\Models\Page;
use App\Orchid\Abstractions\ListScreenPattern;
use App\Orchid\Helpers\OrchidHelper;
use App\Orchid\Layouts\Listeners\SelectListener;
use App\Orchid\Traits\ActivitySignsTrait;
use App\Services\GetUriService;
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

class PageList extends ListScreenPattern
{
    protected ?string $listRedirect = 'platform.pages.list';
    protected ?string $updateRoute = 'platform.pages.edit';

    protected string $name = 'Страницы';

    use ActivitySignsTrait;

    public function query(): iterable
    {
        $this->model = Page::query()->with('parent');
//        dd($this->model->get());
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
                TD::make('name', 'Название')->sort()->filter(),
                TD::make('type', 'Тип')->sort()->render(fn($item) => PagesTypes::from($item->type)->getTitle()),
                TD::make('parent_id', 'Родитель')->render(fn($item) => $item->parent?->name)->sort()->filter(Select::make()->fromModel(Page::class, 'name', 'id'))

                    ->filterValue(fn($item) => Page::find($item)->code),
                TD::make('uri', 'URI')->sort()->filter(),
                TD::make('created_at', 'Дата')->width(100)->alignRight()->sort()
                    ->filter(DateTimer::make()->title('Фильтр по дате')->format('d-m-Y'))
                    ->render(fn($item) => $item->created_at?->format('d-m-Y')),
                TD::make()->width(10)->alignRight()->cantHide()
                    ->render(function ($item) {
                        return Link::make()->icon('wrench')->route($this->updateRoute, $item);
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

    public function chosePageType(Page $item, Request $request): RedirectResponse
    {
        $data = $request->input('item');

        $validator = Validator::make($data, $this->getRules(), $this->getMessages());

        if ($validator->fails()) {
            return redirect()->route($this->listRedirect)->withErrors($validator);
        }

        if ($data['type'] === 'template') {
            $data['type'] = $data['template'];
            unset($data['template']);
        }

        $data['uri'] = (new GetUriService())->getUri($data);
        $page = new Page();
        $page->fill($data)->save();

        return redirect()->route($this->updateRoute, [$page->id])->withInput();
    }

    protected function getRules(): array
    {
        return [
            'code' => ['bail', 'required', 'regex:~^[A-Za-z0-9\-_]+$~', Rule::unique(Page::TABLE_NAME)],
        ];
    }

    protected function getMessages(): array
    {
        return [
            'code.required' => 'Укажите код брэнда',
            'code.regex' => 'В коде допустимы только цифры и латинские буквы',
            'code.unique' => 'Такой код уже используется',
        ];
    }
}

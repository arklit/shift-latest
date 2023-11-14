<?php

namespace App\Orchid\Screens\Pages;

use App\Enums\OrchidRoutes;
use App\Models\Page;
use App\Orchid\Abstractions\ListScreenPattern;
use App\Orchid\Layouts\Listeners\SelectListener;
use App\Orchid\Traits\ActivitySignsTrait;
use App\Services\GetUriService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Support\Facades\Layout;

class PageList extends ListScreenPattern
{
    protected ?string $listRedirect = 'platform.pages.list';
    protected ?string $updateRoute = 'platform.pages.edit';

    use ActivitySignsTrait;

    public function __construct()
    {
        $this->route = OrchidRoutes::INFO_PAGES;
        $this->name = $this->route->getTitle();
    }


    public function query(): iterable
    {
        $this->model = Page::query()->with('parent');
        return parent::query();
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Создать')->modal('choosePageType')
                ->icon('plus')
                ->modalTitle('Выберите тип страницы')
                ->asyncParameters()
                ->method('chosePageType'),
        ];

    }

    public function needPreventsAbandonment(): bool
    {
        return false;
    }

    public function layout(): iterable
    {
        $pages = Page::query()->withDepth()->with('ancestors')->get()->toTree();

        return [
            Layout::view('admin.page-tree.layout', compact('pages')),
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
        if (isset($data['parent_id'])) {
            $parent = Page::query()->where('id', $data['parent_id'])->first();
            $parent->appendNode($page);
        }

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

<?php

namespace App\Orchid\Layouts;

use App\Enums\StaticPages;
use App\Models\InformationPage;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Repository;
use Orchid\Support\Facades\Layout;

class SelectListener extends Listener
{
    protected $targets = [
        'item.type',
    ];

    protected $asyncMethod = 'asyncType';

    protected function layouts(): array
    {
        return [
            Layout::rows([
                Input::make('item.title')
                    ->type('text')
                    ->max(255)
                    ->required()
                    ->title('Название')
                    ->placeholder('Название'),

                Input::make('item.code')
                    ->type('text')
                    ->required()
                    ->title('Код')
                    ->placeholder('Код'),

                Select::make('item.type')
                    ->title('Тип страницы')
                    ->empty('Выберите тип')
                    ->required()
                    ->options([
                        'link' => 'Ссылка',
                        'page' => 'Страница',
                        'sample' => 'Шаблон'
                    ]),

                Select::make('template')
                    ->title('Выбор шаблона')
                    ->options(StaticPages::getOptions())
                    ->required()
                    ->canSee($this->query->get('item.type') === 'sample'),

                Select::make('item.parent_id')
                    ->title('Родительская страница')
                    ->empty('Выберите родителя')
                    ->fromQuery(InformationPage::query()->active(), 'title', 'id'),
            ])
        ];
    }

    public function handle(Repository $repository, Request $request): Repository
    {
        $data = $request->input('item');
        $data['type'] === 'sample' ? $template = $data['template'] : $template = '';
        return $repository
            ->set('item.title', $data['title'])
            ->set('item.code', $data['code'])
            ->set('item.type', $data['type'])
            ->set('item.template', $template)
            ->set('item.parent_id', $data['parent_id']);
    }
}

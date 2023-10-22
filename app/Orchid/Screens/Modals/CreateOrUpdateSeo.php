<?php

namespace App\Orchid\Screens\Modals;

use App\Orchid\Fields\TinyMce;
use Orchid\Screen\Fields\CheckBox;
use App\Orchid\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class CreateOrUpdateSeo
{
    public static function getModal(): Rows
    {
        return Layout::rows([
            Input::make('item.id')->type('hidden'),
            CheckBox::make('item.indexation_off')->title('Закрыть страницу от индексации')->sendTrueOrFalse()->value(false),
            Input::make('item.url')->title('URL')->required(),
            Input::make('item.title')->title('Title ')->required()->maxlength(160)->help('Не более 160 символов'),
            TextArea::make('item.description')->title('Описание')->rows(5),
            Cropper::make('item.image')->title('Изображение к тексту')->targetRelativeUrl(),
            TinyMce::make('item.text')->title('Текст внизу страницы')

        ]);
    }
}

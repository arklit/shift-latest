<?php

namespace App\Orchid\Layouts\StaticPages;

use App\Models\StaticPage;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class ContactsPageLayout extends Rows
{
    function fields(): iterable
    {
        return [
            Upload::make('item.data.certificates')->title('Файл с реквизитами')
                ->groups(StaticPage::CODE_CONTACTS)->maxFiles(1),
        ];
    }
}

//Страница "Контакты"
//Делаем следующие поля:
//Файл с реквизитами - FIle Uploader


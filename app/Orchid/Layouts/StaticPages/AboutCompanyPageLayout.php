<?php

namespace App\Orchid\Layouts\StaticPages;

use App\Helpers\Constants;
use App\Models\StaticPage;
use App\Orchid\Fields\Cropper;
use App\Orchid\Layouts\Repeaters\OurTeamRepeater;
use Nakukryskin\OrchidRepeaterField\Fields\Repeater;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class AboutCompanyPageLayout extends Rows
{
    function fields(): iterable
    {
        return [
            Group::make([
                Input::make('item.data.description_title')->title('Заголовок в описании')->required(),
            ]),

            Group::make([
                Quill::make('item.data.description_text')->title('Текст в описании')->toolbar(Constants::QUILL_TOOLS),
                Cropper::make('item.data.image_main')->title('Заглавное изображение')->targetRelativeUrl(),
            ]),
        ];
    }
}

//Страница "О компании"
//Делаем следующие поля:
//Название страницы - Input
//Заголовок в описании - Input
//Текст в описании - Quill
//Изображение заглавное - Image
//Наши сертификаты - File MultiUploader
//Наша команда (Repeater)
//Изображение сотрудника - Cropper (340x465)
//Должность - Input
//ФИО - Input
//Описание - TextArea
//Номер телефона - Input (Mask: +7 999 999-99-99)
//Адрес электронной почты - Input

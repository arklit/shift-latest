<?php

namespace App\Orchid\Layouts\StaticPages;

use App\Helpers\Constants;
use App\Orchid\Fields\Group;
use App\Orchid\Layouts\Repeaters\AdvantagesRepeater;
use App\Orchid\Layouts\Repeaters\WholesaleOrderRepeater;
use Nakukryskin\OrchidRepeaterField\Fields\Repeater;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Layouts\Rows;

class CooperationPageLayout extends Rows
{
    function fields(): iterable
    {
        return [
//            Input::make('item.data.title')->title('Название страницы')->required(),
            Quill::make('item.data.conditions')->title('Условия сотрудничества')->toolbar(Constants::QUILL_TOOLS),

            Group::make([
                Repeater::make('item.data.advantages')->title('Преимущества')->layout(AdvantagesRepeater::class),
                Repeater::make('item.data.wholesale_order')->title('Как сделать оптовый заказ?')->layout(WholesaleOrderRepeater::class),
            ]),
        ];
    }
}

//Страница "Сотрудничество"
//Делаем следующие поля:
//Название страницы - Input
//Текст "Условия сотрдничество" - Quill



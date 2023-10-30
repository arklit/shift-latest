<?php

namespace App\Orchid\Layouts\StaticPages;

use App\Helpers\Constants;
use App\Orchid\Layouts\Repeaters\ShippingCompaniesRepeater;
use Nakukryskin\OrchidRepeaterField\Fields\Repeater;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Layouts\Rows;

class DeliveryPageLayout extends Rows
{
    function fields(): iterable
    {
        return [
            Quill::make('item.data.rules')->title('Правила доставки')->required(false)->toolbar(Constants::QUILL_TOOLS),
            Repeater::make('item.data.advantages')->title('Транспортные компании')->layout(ShippingCompaniesRepeater::class),
        ];
    }
}

//Страница "Доставка"
    //Правила доставки - Quill


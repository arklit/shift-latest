<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Rows;

class EmptyModal extends Rows
{
    protected function fields(): iterable
    {
        return [];
    }
}

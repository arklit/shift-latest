<?php

namespace App\Orchid\RocontModule\Traits;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;

trait CommandBarUndelitableTrait
{
    public function commandBar()
    {
        return [
            Link::make(__('orchid.go-back'))->icon('arrow-left-circle')->route($this->listRedirect, $this->redirectParams),
            Button::make(__('orchid.save'))->icon('save')->method('save'),
        ];
    }
}

<?php

namespace App\Orchid\RocontModule\Traits;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;

trait CommandBarDeletableTrait
{
    public function commandBar()
    {
        return [
            Link::make(__('orchid.go-back'))->icon('arrow-left-circle')->route($this->listRedirect, $this->redirectParams),
            Button::make('Save')->icon('save')->method('save'),
            Button::make('Delete')->icon('trash')->method('remove')->canSee($this->exists)
                ->confirm('Вы действительно хотите удалить эту запись?'),
        ];
    }
}

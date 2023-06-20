<?php

namespace App\Orchid\Traits;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;

trait CommandBarDeletableTrait
{
    public function commandBar()
    {
        return [
            Link::make(__('orchid.go-back'))->icon('arrow-left-circle')
                ->route($this->redirectTo, $this->redirectParams),
            Button::make('Save')->icon('save')->method('save'),
            Button::make('Delete')->icon('trash')->method('remove')->canSee($this->exists)
                ->confirm('Вы действительно хотите удалить эту запись?'),
        ];
    }
}

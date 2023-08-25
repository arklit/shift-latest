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
            Button::make('Delete')->icon('trash')->class('btn  btn-light')
                ->method('remove')->rawClick()->canSee($this->exists),
            Button::make('Save')->icon('save')->class('btn  btn-success')
                ->method('save'),
        ];
    }
}

<?php

    namespace App\Orchid\Traits;

    use Orchid\Screen\Actions\Button;
    use Orchid\Screen\Actions\Link;

    trait CommandBarDeletableTrait
    {
        public function commandBar(): array
        {
            return [
                Link::make(__('orchid.go-back'))->icon('arrow-left-circle')->route($this->route->list(), $this->redirectParams),
                Button::make('Save')->icon('note')->method('save'),
                Button::make('Delete')->icon('trash')->method('remove')->canSee($this->exists)->rawClick(),
            ];
        }
    }

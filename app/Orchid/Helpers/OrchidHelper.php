<?php

namespace App\Orchid\RocontModule\Helpers;

use App\Enums\OrchidRoutes;
use App\Models\ProtoModel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class OrchidHelper
{
    public static function getYesNoArray(): array
    {
        return [
            'Да' => 'Да',
            'Нет' => 'Нет',
        ];
    }

    public static function setAdminRoutes($code, string $listScreenClass, string $editScreenClass = null): void
    {
        Route::screen("/$code", $listScreenClass)->name("platform.$code.list");
        if (!is_null($editScreenClass)) {
            Route::screen("/$code/create", $editScreenClass)->name("platform.$code.create");
            Route::screen("/$code/{id}/edit", $editScreenClass)->name("platform.$code.edit");
        }
    }

    public static function getPreset(string $title, ?string $block = null)
    {
        $data = config('presets.orchid.' . $title);
        $data = $data ?? [];
        return (is_null($block) || empty($data)) ? $data : $data[$block];
    }

    public static function validate(ProtoModel $item, OrchidRoutes $route, array $data, array $presets)
    {
        $validator = Validator::make($data, $presets['messages'], $presets['rules']);
        $arguments = ($item->exists) ? ['id' => $item->id] : [];
        $route = ($item->exists) ? $route->edit() : $route->create();

        if ($validator->fails()) {
            return redirect()->route($route, $arguments)->withErrors($validator)->withInput();
        }
        return null;
    }
}

<?php

namespace App\Orchid\Helpers;

use App\Enums\OrchidRoutes;
use App\Models\ProtoModel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

    public static function getValidationStructure(string $name, array $defaults = []): array
    {
        $defaultRules = [];
        $existedMessageKeys = [];
        $modelRules = OrchidHelper::getPreset('validators', $name);

        if (empty($defaults)) {
            return $modelRules;
        }
        $defaultsPresets = OrchidHelper::getPreset('validators', 'defaults');

        if (empty($defaultsPresets)) {
            return $modelRules;
        }

        // создаём массив дефолтных правил валидации и сообщений
        foreach ($defaults as $default) {
            $defaultRules['rules'][$default] = $defaultsPresets['rules'][$default];
            $keys = array_keys($defaultsPresets['messages']);

            foreach ($keys as $key) {
                preg_match("~^$default\..*$~", $key, $matches);
                if (!empty($matches)) {
                    $existedMessageKeys[] = $matches[0];
                }
            }

            if (!empty($existedMessageKeys)) {
                foreach ($existedMessageKeys as $key) {
                    $defaultRules['messages'][$key] = $defaultsPresets['messages'][$key];
                }
            }
        }

        // мержим правила модели и дефолтные правила, при этом приоритет отдаётся правилам модели
        return array_merge_recursive($defaultRules, $modelRules);
    }

    public static function setUniqueRule(array $presets, ProtoModel $item, string $fieldName, string $columnName, string $messageName): array
    {
        $presets['rules'][$fieldName][] = Rule::unique($item->getTable(), $columnName)->ignore($item->id);
        $presets['messages']['title.' . $fieldName] = "Такой $messageName уже используется";
        return $presets;
    }

    public static function validate(ProtoModel $item, OrchidRoutes $route, array $data, array $presets)
    {

        $validator = Validator::make($data, $presets['rules'], $presets['messages']);
        $route = ($item->exists) ? $route->edit() : $route->create();

        if ($validator->fails()) {
            return redirect()->route($route, $item->id)->withErrors($validator)->withInput();
        }
        return null;
    }
}

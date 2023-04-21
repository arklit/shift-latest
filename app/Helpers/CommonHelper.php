<?php

    namespace App\Helpers;

    use Illuminate\Support\Arr;

    class CommonHelper
    {

        public static function isEmpty($mixed): bool
        {
            if (is_array($mixed)) {
                $flatten = Arr::flatten($mixed);
                $filtered = collect($flatten)->filter(fn($item) => !empty($item));
                return $filtered->isEmpty();
            }
            return empty($mixed);
        }

        public static function getPreset(string $title, ?string $block = null)
        {
            $data = config('presets.' . $title);
            $data = $data ?? [];
            return (is_null($block) || empty($data)) ? $data : $data[$block];
        }
    }

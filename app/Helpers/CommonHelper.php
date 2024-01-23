<?php

namespace App\Helpers;

use App\Enums\ClientRoutes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Breadcrumbs;
use Tabuna\Breadcrumbs\Trail;

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

    public function extractValidationRulesAndMessages($form): array
    {
        $validationRules = [];
        $validationMessages = [];

        $this->processFields($form['fields'], '', $validationRules, $validationMessages);

        return [
            'rules' => $validationRules,
            'messages' => $validationMessages
        ];
    }

    private function processFields($fields, $parentPath, &$validationRules, &$validationMessages): void
    {
        foreach ($fields as $fieldName => $field) {
            $path = $parentPath ? $parentPath . '.' . $fieldName : $fieldName;
            if (isset($field['rules'])) {
                foreach ($field['rules'] as $ruleValue) {
                    $rule = $this->convertVuelidateRulesToLaravel($ruleValue);
                    $validationRules[$path][] = $ruleValue;
                    $validationMessages[$path . '.' . $rule] = $field['messages'][$rule];
                }
            }

            if (str_contains($fieldName, 'fields_')) {
                foreach ($field as $subFieldName => $subField) {
                    if (isset($subField['rules'])) {
                        $subPath = $subFieldName;
                        foreach ($subField['rules'] as $subRuleValue) {
                            $subRule = $this->convertVuelidateRulesToLaravel($subRuleValue);
                            $validationRules[$subPath][] = $subRuleValue;
                            $validationMessages[$subPath . '.' . $subRule] = $subField['messages'][$subRule];
                        }
                    }
                }
            }

            if (isset($field['fields']) && is_array($field['fields'])) {
                $this->processFields($field['fields'], $path, $validationRules, $validationMessages);
            }
        }
    }

    private function convertVuelidateRulesToLaravel($ruleValue): string
    {
        if (str_contains($ruleValue, ':')) {
            $ruleParts = explode(':', $ruleValue);
            return $ruleParts[0];
        } else {
            return $ruleValue;
        }
    }
}

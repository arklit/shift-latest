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

        $this->processFields($form['form'], '', $validationRules, $validationMessages);

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
                foreach ($field['rules'] as $ruleKey => $ruleValue) {
                    $rule = $this->convertVuelidateRulesToLaravel($ruleKey, $ruleValue);
                    $validationRules[$path][] = $rule;
                    $validationMessages[$path . '.' . $ruleKey] = $field['messages'][$ruleKey];
                }
            }

            if (str_contains($fieldName, 'fields_')) {
                foreach ($field as $subFieldName => $subField) {
                    if (isset($subField['rules'])) {
                        $subPath = $subFieldName;
                        foreach ($subField['rules'] as $subRuleKey => $subRuleValue) {
                            $subRule = $this->convertVuelidateRulesToLaravel($subRuleKey, $subRuleValue);
                            $validationRules[$subPath][] = $subRule;
                            $validationMessages[$subPath . '.' . $subRuleKey] = $subField['messages'][$subRuleKey];
                        }
                    }
                }
            }

            if (isset($field['form']) && is_array($field['form'])) {
                $this->processFields($field['form'], $path, $validationRules, $validationMessages);
            }
        }
    }

    function convertVuelidateRulesToLaravel($vuelidateRule, $ruleValue): string
    {
        return match ($vuelidateRule) {
            'required' => 'required',
            'requiredIf' => 'required_if:' . $ruleValue,
            'requiredUnless' => 'required_unless:' . $ruleValue,
            'minLength' => 'min:' . $ruleValue,
            'maxLength' => 'max:' . $ruleValue,
            'email' => 'email',
            'numeric' => 'numeric',
            'alpha' => 'alpha',
            'alphaNum' => 'alpha_num',
            'alphaDash' => 'alpha_dash',
            'alphaSpace' => 'alpha_space',
            'regex' => 'regex:' . $ruleValue,
            'unique' => 'unique:' . $ruleValue,
            'confirmed' => 'same:' . $ruleValue,
            'in' => 'in:' . $ruleValue,
            default => $vuelidateRule
        };
    }
}

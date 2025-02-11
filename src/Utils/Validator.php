<?php

namespace RevvoApi\Utils;

class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            foreach ($rulesArray as $rule) {
                if ($rule === 'required' && empty($data[$field])) {
                    $errors[$field][] = "$field é obrigatório.";
                }
                if ($rule === 'string' && !is_string($data[$field] ?? null)) {
                    $errors[$field][] = "$field deve ser uma string.";
                }
                if ($rule === 'integer' && !filter_var($data[$field] ?? null, FILTER_VALIDATE_INT)) {
                    $errors[$field][] = "$field deve ser um inteiro.";
                }
                if (str_starts_with($rule, 'max:')) {
                    $max = (int) explode(':', $rule)[1];
                    if (isset($data[$field]) && strlen($data[$field]) > $max) {
                        $errors[$field][] = "$field não deve exceder $max caracteres.";
                    }
                }
                if (str_starts_with($rule, 'min:')) {
                    $min = (int) explode(':', $rule)[1];
                    if (isset($data[$field]) && strlen($data[$field]) < $min) {
                        $errors[$field][] = "$field deve ter pelo menos $min caracteres.";
                    }
                }
            }
        }

        return $errors;
    }
}
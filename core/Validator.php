<?php
    namespace core;

    class Validator
    {
        protected $errors = [];

        public function validate(array $data, array $rules, array $messages = []): bool
        {
            foreach ($rules as $field => $fieldRules) {
                $value = $data[$field] ?? null;

                foreach ($fieldRules as $rule) {
                    if (strpos($rule, ':') !== false) {
                        [$ruleName, $ruleValue] = explode(':', $rule);
                    } else {
                        $ruleName = $rule;
                        $ruleValue = null;
                    }

                    $method = 'validate' . ucfirst($ruleName);
                    if (!method_exists($this, $method)) {
                        throw new \Exception("Regla de validación '$ruleName' no implementada.");
                    }

                    if (!$this->{$method}($field, $value, $ruleValue, $data)) {
                        break;
                    }
                }
            }

            return empty($this->errors);
        }

        public function errors(): array{
            return $this->errors;
        }

        // Métodos de validación

        protected function validateRequired($field, $value, $param): bool{
            if (empty($value)) {
                $this->errors[$field][] = "El campo $field es obligatorio.";
                return false;
            }
            return true;
        }

        protected function validateEmail($field, $value): bool{
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field][] = "El campo $field debe ser un email válido.";
                return false;
            }
            return true;
        }

        protected function validateMin($field, $value, $min): bool
        {
            if (strlen($value) < $min) {
                $this->errors[$field][] = "El campo $field debe tener al menos $min caracteres.";
                return false;
            }
            return true;
        }

        protected function validateMax($field, $value, $max): bool
        {
            if (strlen($value) > $max) {
                $this->errors[$field][] = "El campo $field no debe exceder los $max caracteres.";
                return false;
            }
            return true;
        }

        protected function validateSame($field, $value, $other, $data): bool
        {
            if (!isset($data[$other]) || $value !== $data[$other]) {
                $this->errors[$field][] = "El campo $field debe coincidir con $other.";
                return false;
            }
            return true;
        }
        
    }
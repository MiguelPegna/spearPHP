<?php
    namespace core;

    abstract class Request
    {
        protected array $data;
        protected array $errors = [];
        protected array $messages = [];


        public function __construct(array $data){
            $this->data = $data;
        }

        abstract public function rules(): array;

        public function validate(): bool{
            $validator = new Validator();
    
            // Si hay mensajes personalizados, se pasan al validador
            $messages = $this->messages();
    
            if (!$validator->validate($this->data, $this->rules(), $messages)) {
                $this->errors = $validator->errors();
                return false;
            }
    
            return true;
        }

        public function errors(): array{
            return $this->errors;
        }

        public function messages(): array{
            return $this->messages;
        }

        public function input(string $key, $default = null){
            return $this->data[$key] ?? $default;
        }

        public function all(): array{
            return $this->data;
        }

        protected function sanitize(array $data): array{
            foreach ($data as $key => $value) {
                $data[$key] = $this->clean($value);
            }
            return $data;
        }

        protected function clean($value){
            if (!is_string($value)) return $value;

            $string = preg_replace('/\s+/', ' ', trim($value));
            $string = strip_tags($string);

            $patterns = [
                '/<script\b[^>]*>(.*?)<\/script>/is',
                '/SELECT\s+\*\s+FROM/i',
                '/DELETE\s+FROM/i',
                '/INSERT\s+INTO/i',
                '/DROP\s+TABLE/i',
                '/OR\s+\'1\'=\'1\'/i',
                '/OR\s+\"1\"=\"1\"/i',
                '/--/i',
                '/\b(is NULL|LIKE|OR)\b/i',
                '/\^|\[|\]|==/',
            ];

            return preg_replace($patterns, '', $string);
        }
    }

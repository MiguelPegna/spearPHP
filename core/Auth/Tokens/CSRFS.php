<?php

    namespace core\Auth\Tokens;
    
    class CSRFS {
        const TOKEN_KEY = '_csrf_token';

        public static function generate(): string {
            if (!isset($_SESSION[self::TOKEN_KEY])) {
                $_SESSION[self::TOKEN_KEY] = bin2hex(random_bytes(32));
            }
            return $_SESSION[self::TOKEN_KEY];
        }

        public static function verify(string $token): bool {
            return isset($_SESSION[self::TOKEN_KEY]) && hash_equals($_SESSION[self::TOKEN_KEY], $token);
        }

        public static function getToken(): string {
            $token = self::generate();
            return '<input type="hidden" name="_csrf_token" value="'.$token.'">';
        }
    }
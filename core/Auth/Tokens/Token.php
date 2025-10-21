<?php

    namespace core\Auth\Tokens;
    use core\Exceptions\Auth\TokenException;

    class Token{

        public function generateToken(): string{
            return $this->createToken(); // build token
        }

        //creation the refreshToken
        public function createToken(){
            return bin2hex(random_bytes(32)); // make random token of 64 characters
        }

        public function getTokenExpiry($days = 7):string{
            return date('Y-m-d H:i:s', strtotime("+{$days} days")); //create date of expire for refreshToken
        }

        /**
         * Valida si un token tiene la longitud esperada.
         */
        public function validateTokenFormat(string $token, int $length = 64): bool{
            if (strlen($token) !== $length) {
                throw new TokenException("Invalid Token or modified.");
            }
            return true;
        }

        /**
         * Verifica si la fecha de expiración ha pasado.
         */
        public function isTokenExpired(string $expiresAt): bool{
            return strtotime($expiresAt) < time();
        }
    }
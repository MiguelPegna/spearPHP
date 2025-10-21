<?php

    namespace core\Auth\Tokens;

    use core\Exceptions\Tokens\JWTException;

    class JWT{
        private $JWT_KEY;

        public function __construct($jwtKey='')
        {
            $this->JWT_KEY = $jwtKey ?: env('JWT_KEY');
        }

        public function createJWT($payload): string
        {
            if(!array($payload) || empty($payload)) throw new JWTException(config('jwt.invalid'), 403);
            $payload ['exp'] = time() + env('JWT_TIME');
            return $this->buildJWT($payload);
        }//end method

        public function buildJWT($payload): string
        {
            $header = ['alg' => 'HS256', 'typ' => 'JWT'];
            $headerEncoded = $this->encodeString(json_encode($header));
            $payloadEncoded = $this->encodeString(json_encode($payload));
            $signature = $this->encodeSignature($headerEncoded, $payloadEncoded);
            $signatureEncoded = $this->encodeString($signature);
            $jwt = "$headerEncoded.$payloadEncoded.$signatureEncoded";
            return $jwt;
        }

        public function verifyJWT($jwt): array|false
        {
            if($jwt === '') return false;
            $segments = $this->splitJWT($jwt);
            if ($segments === false) return false;
            [$headerEncoded, $payloadEncoded, $signatureEncoded] = $segments;
            $payload = json_decode($this->decodeString($payloadEncoded), true);
            if($payload['exp'] < time() ) return false;
            $expectedSignature = $this->encodeSignature($headerEncoded, $payloadEncoded);
            $expectedsignatureEncoded = $this->encodeString($expectedSignature);
            return hash_equals($signatureEncoded, $expectedsignatureEncoded) ? $payload : false;
            
        }

        public function splitJWT($jwt): array|false
        {
            isset($jwt) && $jwt !== '' ? $segments = explode('.', $jwt) : $segments = [];
            if(count($segments) !== 3 || $jwt === '') return false; //throw new JWTException(JWT_INCORRECT, 403);
            return $segments;
        }

        //codify and clean header in base64
        private function encodeString($data): string
        {
            if($data === null) return false;
            return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
        }
        //decodify and restore string to original version of
        private function decodeString($data): string
        {
            if($data === null) return false;
            return base64_decode(strtr($data, '-_', '+/'));
        }

        private function encodeSignature($headerEncoded, $payloadEncoded):string
        {
            return hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->JWT_KEY, true);
        }

    }
<?php

    namespace core\Exceptions\Tokens;

    use core\Exceptions\CoreException;

    class JWTException extends CoreException{
        public function __construct($message = "Error in JWT"){
            parent::__construct($message, 401);
        }
    }
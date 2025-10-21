<?php

    namespace core\Exceptions\Auth;

    use core\Exceptions\CoreException;

    class TokenException extends CoreException{
        public function __construct($message = "Error in Token Authentication."){
            parent::__construct($message, 500);
        }
    }
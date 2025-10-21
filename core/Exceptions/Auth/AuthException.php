<?php

    namespace core\Exceptions\Auth;

    use core\Exceptions\CoreException;

    class AuthException extends CoreException{
        public function __construct($message = "Error in Authentication."){
            parent::__construct($message, 500);
        }
    }
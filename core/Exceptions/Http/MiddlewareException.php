<?php

    namespace core\Exceptions\Http;

    use core\Exceptions\CoreException;

    class MiddlewareException extends CoreException{
        public function __construct($message = "Error in middleware."){
            parent::__construct($message, 403);
        }
    }
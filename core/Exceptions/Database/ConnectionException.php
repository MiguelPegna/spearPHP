<?php

    namespace core\Exceptions\Database;

    use core\Exceptions\CoreException;

    class ConnectionException extends CoreException{
        public function __construct($message = "Error connecting to database."){
            parent::__construct($message, 500);
        }
    }
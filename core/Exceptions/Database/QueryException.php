<?php

    namespace core\Exceptions\Database;

    use core\Exceptions\CoreException;

    class QueryException extends CoreException{
        public function __construct($message = "Database query failed."){
            parent::__construct($message, 400);
        }
    }
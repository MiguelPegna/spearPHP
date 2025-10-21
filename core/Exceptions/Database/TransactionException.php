<?php

    namespace core\Exceptions\Database;

    use core\Exceptions\CoreException;

    class TransactionException extends CoreException{
        public function __construct($message = "Database transaction error."){
            parent::__construct($message, 500);
        }
    }
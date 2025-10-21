<?php

    namespace core\Database;
    use core\Exceptions\Database\ConnectionException;

    class DB{
        protected static ?Statements $statements = null;

        public function __construct(?string $server = null) {
            if (self::$statements === null) {
                $this->connect($server);
            }
        }

        protected function connect(?string $server = null): void {
            $dbManager = new Manager();
            $dbConnection = new Connections();

            $dbInfo = $dbManager->get($server);
            $connection = $dbConnection->connect($dbInfo);

            if ($connection === null) {
                throw new ConnectionException("The connection to the database could not be established.");
            }

            self::$statements = new Statements($connection);
        }

        public function statements(): Statements {
            return self::$statements;
        }
    }
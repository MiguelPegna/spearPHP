<?php

    namespace core\Database;

    use PDO;
    use PDOException;
    use core\Exceptions\Database\ConnectionException;

    class Connections
    {
        private ?PDO $connection = null;
        private array $dbInfo;

        public function connect(array $dbInfo): ?PDO
        {
            $this->dbInfo = $dbInfo;
            $driver = $dbInfo['driver'] ?? 'mysql';

            try {
                switch ($driver) {
                    case 'mysql':
                        return $this->connection = $this->connectMySQL();
                    case 'pgsql':
                        return $this->connection = $this->connectPostgreSQL();
                    case 'sqlsrv':
                        return $this->connection = $this->connectSQLServer();
                    default:
                        throw new ConnectionException("Database driver '{$driver}' not supported.");
                }
            } catch (PDOException $e) {
                throw new ConnectionException("PDO Error: " . $e->getMessage());
            }
        }

        private function createPDO(string $dsn, string $user, string $pass, array $options = []): PDO
        {
            $pdo = new PDO($dsn, $user, $pass, $options);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }

        private function connectMySQL(): PDO
        {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                $this->dbInfo['host'],
                $this->dbInfo['database'],
                $this->dbInfo['charset'] ?? 'utf8mb4'
            );

            $pdo = $this->createPDO($dsn, $this->dbInfo['user'], $this->dbInfo['password']);
            $pdo->exec('SET NAMES utf8mb4');
            return $pdo;
        }

        private function connectPostgreSQL(): PDO
        {
            $dsn = sprintf(
                "pgsql:host=%s;dbname=%s",
                $this->dbInfo['host'],
                $this->dbInfo['database']
            );

            return $this->createPDO($dsn, $this->dbInfo['user'], $this->dbInfo['password']);
        }

        private function connectSQLServer(): PDO
        {
            $dsn = sprintf(
                "sqlsrv:Server=%s;Database=%s",
                $this->dbInfo['host'],
                $this->dbInfo['database']
            );

            return $this->createPDO($dsn, $this->dbInfo['user'], $this->dbInfo['password']);
        }

        public function getConnection(): ?PDO
        {
            return $this->connection;
        }
    }

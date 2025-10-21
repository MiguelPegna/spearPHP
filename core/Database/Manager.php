<?php
 
    namespace core\Database;
    use core\Exceptions\Database\ConnectionException;

    class Manager{
        protected array $connections = [];
        protected string $default;
        
        public function __construct(){
            $this->connections = config('database.connections');
            $this->default = config('database.default') ?? array_key_first($this->connections);
        }

        public function get($key = null) : array {
            return $this->connections[$key ?? $this->default] ?? throw new ConnectionException("DB config '$key' not found.");
        }

        public function getDefault(): string {
            return $this->default;
        }
    }
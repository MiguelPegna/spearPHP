<?php
    namespace core\Auth\Guards;

    use core\Contracts\AuthGuardInterface;

    class SessionGuard implements AuthGuardInterface{
        protected string $sessionKey;
        protected string $idKey;

        public function __construct(string $sessionKey = 'user', string $idKey = 'id') {
            $this->sessionKey = $sessionKey;
            $this->idKey = $idKey;

            if(session_status() !== PHP_SESSION_ACTIVE) session_start();
        }

        public function user() {
            return $_SESSION[$this->sessionKey] ?? null;
        }

        /**
         * Retorna el ID del usuario
         */
        public function id(): ?int {
            $user = $this->user();
            return $user[$this->idKey] ?? null;
        }


        public function validate(): bool {
            return isset($_SESSION[$this->sessionKey]);
        }

        public function login(array $userData) {
            $_SESSION[$this->sessionKey] = $userData; // ejemplo
            return true;
        }

        public function logout(): void {
            unset($_SESSION[$this->sessionKey]);
            session_destroy();
        }
    }
<?php
    namespace core\Contracts;

    interface AuthGuardInterface{
        /**
         * Retorna el usuario autenticado (o null si no lo hay).
         */
        public function user();

        /**
         * Retorna true si hay un usuario autenticado.
         */
        public function validate(): bool;

        /**
         * Retorna el ID del usuario autenticado.
         */
        public function id(): ?int;

        /**
         * Realiza el login y retorna el token o sesión creada.
         */
        public function login(array $credentials);

        /**
         * Cierra la sesión o invalida el token.
         */
        public function logout(): void;
    }
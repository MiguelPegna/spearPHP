<?php

    namespace core\Auth\Guards;

    use core\Contracts\AuthGuardInterface;
    use core\Auth\Tokens\JWT;

    class JWTGuard implements AuthGuardInterface
    {
        protected ?array $payload = null;
        protected JWT $jwt;
        protected ?string $token = null;

        /**
         * @param string $jwtKey Clave secreta para generar/verificar JWT
         * @param string|null $token Token JWT a validar (opcional)
         */
        public function __construct(string $jwtKey, ?string $token = null)
        {
            $this->jwt = new JWT($jwtKey);
            $this->token = $token;

            if ($this->token !== null) {
                $this->payload = $this->jwt->verifyJWT($this->token);
            }
        }

        /**
         * Establece un token JWT y lo verifica
         */
        public function setToken(string $token): void
        {
            $this->token = $token;
            $this->payload = $this->jwt->verifyJWT($this->token);
        }

        /**
         * Devuelve la información del usuario decodificada del JWT
         * @return array|null
         */
        public function user(): ?array
        {
            return $this->payload;
        }

        /**
         * Valida si hay un JWT válido
         */
        public function validate(): bool
        {
            return $this->payload !== null;
        }

        /**
         * Retorna el ID del usuario
         */
        public function id(): ?int
        {
            return $this->payload['id'] ?? null;
        }

        /**
         * Crea un JWT a partir de los datos del usuario
         * @param array $userData Datos del usuario (al menos 'id')
         * @return string JWT generado
         */
        public function login(array $userData): string
        {
            // Guardar payload normalizado
            $this->payload = $userData;
            return $this->jwt->createJWT($userData);
        }

        /**
         * Invalida el token y borra la información del usuario
         */
        public function logout(): void
        {
            $this->payload = null;
            $this->token = null;
            // Opcional: agregar a blacklist si se implementa
        }
        
    }

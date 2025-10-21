<?php
    namespace core\Auth\Guards;

    use core\Contracts\AuthGuardInterface;
    use core\Auth\Tokens\Token;

    class TokenGuard implements AuthGuardInterface
    {
        protected Token $token;
        protected int $length;

        public function __construct(int $length = 64)
        {
            $this->token = new Token();
            $this->length = $length;
        }

        public function user()
        {
            // Retorna info del usuario asociado al token
        }

        public function validate(): bool
        {
            // Validar token
        }

        public function id(): ?int
        {
            // Retornar ID del usuario
        }

        public function login(array $credentials)
        {
            return $this->token->createToken();
        }

        public function logout(): void
        {
            // Invalidar token
        }
    }
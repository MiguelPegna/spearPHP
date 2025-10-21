<?php

    namespace core\Auth;

    use core\Contracts\AuthGuardInterface;
    use core\Exceptions\Auth\AuthException;

    use core\Auth\Guards\SessionGuard;
    use core\Auth\Guards\JWTGuard;
    use core\Auth\Guards\TokenGuard;
    use core\Auth\Guards\OAuth2Guard;

    /**
     * Class Manager
     * 
     * Gestiona los guards de autenticación definidos en config/auth.php
     * Permite delegar llamadas directamente al guard activo.
     */
    class Manager
    {
        /**
         * Guard activo
         * 
         * @var AuthGuardInterface
         */
        protected AuthGuardInterface $guard;

        /**
         * Constructor
         * 
         * @param array $params Parámetros opcionales adicionales para el constructor del guard
         * @throws AuthException
         */
        public function __construct(array $params = [])
        {
            $config = config('auth');
            $defaultGuard = $config['default'] ?? 'jwt';
            $guards = $config['guards'] ?? [];

            $guardConfig = $guards[$defaultGuard] ?? null;
            if (!$guardConfig) {
                throw new AuthException("Guard [$defaultGuard] not config.");
            }

            $guardName = $guardConfig['driver'] ?? 'jwt';

            $classMap = [
                'session' => SessionGuard::class,
                'jwt'     => JWTGuard::class,
                'token'   => TokenGuard::class,
                'oauth'   => OAuth2Guard::class,
            ];

            if (!isset($classMap[$guardName])) {
                throw new AuthException("Driver auth [$guardName] not allowed.");
            }

            // Parámetros definidos en config/auth.php
            $guardParams = $guardConfig['driver_params'] ?? [];
            // Mezclar con parámetros adicionales pasados al constructor
            $guardParams = array_merge($guardParams, $params);

            $guardClass = $classMap[$guardName];

            // Instanciamos el guard
            $this->guard = new $guardClass(...$guardParams);
        }

        /**
         * Delegar cualquier método al guard activo
         * 
         * Esto permite hacer:
         * $auth = new Manager();
         * $auth->login($credentials);
         * 
         * @param string $method
         * @param array $args
         * @return mixed
         */
        public function __call(string $method, array $args)
        {
            if (!method_exists($this->guard, $method)) {
                throw new AuthException("Method [$method] Not available in active guard.");
            }

            return $this->guard->$method(...$args);
        }

        /**
         * Obtener el guard activo
         * 
         * @return AuthGuardInterface
         */
        public function guard(): AuthGuardInterface
        {
            return $this->guard;
        }
    }

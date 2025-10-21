<?php

    namespace core;

    use core\Exceptions\Http\MiddlewareException;
    use core\Contracts\MiddlewareInterface;

    class Route{
        private static $routes = [];
        private static array $groupAttributes = [];
        private static array $namedRoutes = [];

        private $middleware = [];
        private $Route;
        private $name;

        public static function get($uri, $function){
            $route = new self();
            $uri = self::applyGroupPrefix($uri);
            $route->Route = ['method' => 'GET', 'uri' => $uri, 'callback' => $function];
            $route->applyGroupMiddleware();
            self::$routes['GET'][$uri] = $route;
            return $route;
        }

        public static function post($uri, $function){
            $route = new self();
            $uri = self::applyGroupPrefix($uri);
            $route->Route = ['method' => 'POST', 'uri' => $uri, 'callback' => $function];
            $route->applyGroupMiddleware();
            self::$routes['POST'][$uri] = $route;
            return $route;
        }

        // Método para PUT
        public static function put($uri, $function){
            $route = new self();
            $uri = self::applyGroupPrefix($uri);
            $route->Route = ['method' => 'PUT', 'uri' => $uri, 'callback' => $function];
            $route->applyGroupMiddleware();
            self::$routes['PUT'][$uri] = $route;
            return $route;
        }

        // Método para PATCH
        public static function patch($uri, $function){
            $route = new self();
            $uri = self::applyGroupPrefix($uri);
            $route->Route = ['method' => 'PATCH', 'uri' => $uri, 'callback' => $function];
            $route->applyGroupMiddleware();
            self::$routes['PATCH'][$uri] = $route;
            return $route;
        }

        // Método para DELETE
        public static function delete($uri, $function){
            $route = new self();
            $uri = self::applyGroupPrefix($uri);
            $route->Route = ['method' => 'DELETE', 'uri' => $uri, 'callback' => $function];
            $route->applyGroupMiddleware();
            self::$routes['DELETE'][$uri] = $route;
            return $route;
        }

        public function name(string $name){
            $this->name = $name;
            // Puedes almacenar en un array estático para buscarlas después
            self::$namedRoutes[$name] = $this;
            return $this;
        }

        public static function url(string $name, array $params = []): string {
            if (!isset(self::$namedRoutes[$name])) {
                throw new \Exception("Route name '{$name}' not found.");
            }
            $route = self::$namedRoutes[$name]->Route['uri'];
            // Reemplazar parámetros en la ruta, ej: /user/:id -> /user/123
            foreach ($params as $key => $value) {
                $route = preg_replace('#:' . $key . '#', $value, $route);
            }
            return $route;
        }

        public static function group($attributes, $callback){
            $original = self::$groupAttributes;

            self::$groupAttributes = array_merge_recursive(self::$groupAttributes, $attributes);

            $callback(); // Ejecutar las rutas dentro del grupo

            self::$groupAttributes = $original; // Restaurar valores previos
        }

        private static function applyGroupPrefix($uri){
            $prefix = self::$groupAttributes['prefix'] ?? '';
            return $prefix . $uri;
        }

        private function applyGroupMiddleware(){
            if (!empty(self::$groupAttributes['middleware'])) {
                $groupMiddleware = is_array(self::$groupAttributes['middleware'])
                    ? self::$groupAttributes['middleware']
                    : [self::$groupAttributes['middleware']];
                $this->middleware = array_merge($this->middleware, $groupMiddleware);
            }
        }

        public function middleware($key){
            $middlewares = is_array($key) ? $key : [$key];
            $this->middleware = array_merge($this->middleware, $middlewares);
            return $this;
        }

        public static function refer(){
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $path = rtrim($path, '/');
            if ($path === '') $path = '/';

            $method = $_SERVER['REQUEST_METHOD'];

            // Simular PUT, PATCH, DELETE
            if ($method === 'POST' && isset($_POST['_method'])) {
                $fakeMethod = strtoupper($_POST['_method']);
                if (in_array($fakeMethod, ['PUT', 'PATCH', 'DELETE'])) {
                    $method = $fakeMethod;
                }
            }

            self::findRoute($path, $method);
        }

        public static function findRoute(string $path, string $method){

            if (!isset(self::$routes[$method])) return self::notFound();

            foreach (self::$routes[$method] as $route => $object) {
                if ($object instanceof self) {
                    //$pattern = preg_replace('#:[a-zA-Z0-9_]+#', '([a-zA-Z0-9_-]+)', $route);
                    $pattern = preg_replace('#:([\w]+)#', '([^/]+)', $route);
                    
                    if (preg_match("#^$pattern$#", $path, $matches)) {
                        $params = array_slice($matches, 1);
                        
                        foreach ($object->middleware as $middleware) {
                            self::middlewareExecute($middleware);
                        }
                        $callback = $object->Route['callback'];
                        $response = null;

                        if (is_callable($callback)) {
                            $response = $callback(...$params);
                        }
                        elseif (is_array($callback)) {
                            $controller = new $callback[0];
                            $response = $controller->{$callback[1]}(...$params);
                        }

                        if (is_array($response) || is_object($response)) {
                            header('Content-Type: application/json');
                            echo json_encode($response);
                        } else {
                            echo $response;
                        }
                        return;
                    }
                }
            }
            self::notFound();
        }

        public static function notFound(){
            http_response_code(404);
            echo '404 Not Found';
        }

        public static function middlewareExecute($middlewareAlias){
            $middlewares = include __DIR__ . '/../bootstrap/kernel.php';
            
            // Detectar si el middleware tiene parámetros, ejemplo: 'role:admin'
            $parts = explode(':', $middlewareAlias, 2);
            $key = $parts[0];
            //$params = $parts[1] ?? null;
            $params = isset($parts[1]) ? explode(',', $parts[1]) : [];

            if (!isset($middlewares[$key])) throw new MiddlewareException("Middleware no registrado: $key");

            $class = $middlewares[$key];

            if (!class_exists($class)) throw new MiddlewareException("Middleware not found: $class");

            $instance = new $class(...$params);

            if (!$instance instanceof MiddlewareInterface) throw new MiddlewareException("El middleware debe implementar MiddlewareInterface.");

            // Pasar parámetros si el middleware los acepta
            if ($params !== null) {
                $instance->handle($params);
            } else {
                $instance->handle(); //execute middleware
            }
        }
    }

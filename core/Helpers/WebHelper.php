<?php

    namespace core\Helpers;

    class WebHelper{

        /**
        * Redirecciones y url
        */

        public static function redirect(string $routeName, array $params = []) {
            $url = \core\Route::url($routeName, $params);
            if ($url) {
                header("Location: $url");
                exit;
            }
            // Si no existe la ruta, por seguridad redirige a home o error
            header("Location: /");
            exit;
        }

        // Obtener URL actual
        public static function currentUrl(): string {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
            return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        
        /**
         * ----------------------------------------
         * JSON y arrays
         * ----------------------------------------
         */

        /**
        * return response of request
        * @param bool $status => true or false
        * @param string $msg => datail message of the response
        * @param int $code => code http of the response
        */
        public static function response($status, $msg, $code){
            return ['status' => $status, 'msg' => $msg, 'code'=> $code];
        }

        public static function toJson($data, bool $pretty = true): string {
            return json_encode($data, $pretty ? JSON_PRETTY_PRINT : 0);
        }

        public static function fromJson(string $json, bool $assoc = true) {
            return json_decode($json, $assoc);
        }

        // Aplanar arrays multidimensionales
        public static function arrayFlatten(array $array): array {
            $result = [];
            array_walk_recursive($array, function($a) use (&$result) { $result[] = $a; });
            return $result;
        }


        /**
         * Renderiza vistas, componentes, modals o layouts de manera flexible.
         *
         * @param string $path     Ruta relativa del archivo (sin extensión .php)
         * @param array  $data     Datos que se pasarán a la vista
         * @param bool   $return   Si true devuelve el HTML como string, si false imprime directamente
         * @param string $type     Tipo de recurso: 'view', 'component', 'modal', 'layout'
         * @param string $module   Módulo al que pertenece la vista, si aplica
         * @return string|null
         * @throws Exception
         */
        public static function render(string $path, array $data = [], bool $return = false, string $type = 'view', string $module = '') {
            // Carpetas base según tipo
            $baseFolders = [
                'view'      => 'views',
                'component' => 'views/_components',
                'modal'     => 'views/_layouts/modals',
                'layout'    => 'views/_layouts'
            ];

            $base = $baseFolders[$type] ?? 'views';

            // Carpeta de módulo si se define
            if ($module) {
                $base .= '/' . $module;
            }

            $file = rtrim($base, '/') . '/' . $path . '.php';

            if (!file_exists($file)) {
                throw new \Exception("File not found: $file");
            }

            // Hacer que las claves del array $data sean variables accesibles en la vista
            extract($data);

            if ($return) {
                ob_start();
                require $file;
                return ob_get_clean();
            } else {
                require $file;
                return null;
            }
        }

        /**
        * Crea una cookie con las configuraciones especificadas.
        * @param string $name Nombre de la cookie.
        * @param string $value Valor de la cookie.
        * @param int $expires Tiempo de expiración en segundos desde ahora.
        * @param bool $secure Indica si la cookie solo debe enviarse por HTTPS.
        * @param bool $httpOnly Indica si la cookie solo es accesible desde el backend.
        * @param string $sameSite Política SameSite para la cookie.
        * @param string $path Ruta en la que la cookie es válida.
        */
        public static function setCustomCookie($name, $value, $expires, $secure = null, $httpOnly = true, $sameSite = 'Strict', $path = '/') {
            
            if ($secure === null) {
                $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            }
            setcookie($name, $value, [
                'expires' => time() + $expires,
                'path' => $path,
                'secure' => $secure,
                'httponly' => $httpOnly,
                'samesite' => $sameSite,
            ]);
        }

        /**
        * Crea múltiples cookies con configuraciones predefinidas.
        *
        * @param array $cookies Array de cookies con estructura:
        *        [
        *          ['name' => 'cookie_name', 'value' => 'cookie_value', 'expires' => tiempo_en_segundos],
        *          ...
        *        ]
        * @param bool $secure Indica si las cookies solo deben enviarse por HTTPS.
        * @param bool $httpOnly Indica si las cookies solo son accesibles desde el backend.
        * @param string $sameSite Política SameSite para las cookies.
        * @param string $path Ruta en la que las cookies son válidas.
        */
        public static function setCookies(array $cookies, $secure = null, $httpOnly = true, $sameSite = 'Strict', $path = '/') {
            if ($secure === null) {
                $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            }
            foreach ($cookies as $cookie) {
                // Evitar errores si falta algún dato obligatorio
                if (!isset($cookie['name'], $cookie['value'], $cookie['expires'])) continue;
                
                self::setCustomCookie(
                    $cookie['name'],
                    $cookie['value'],
                    $cookie['expires'],
                    $secure,
                    $httpOnly,
                    $sameSite,
                    $path
                );
            }
        }
    }
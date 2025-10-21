<?php

    namespace core;

    use core\Exceptions\CoreException;
    use Throwable;

    class App{
        public static function run(): void {
            try {
                $uri = $_SERVER['REQUEST_URI'];
                if (str_starts_with($uri, '/api')) {
                    require_once __DIR__ . '/../routes/api.php';
                } else {
                    require_once __DIR__ . '/../routes/web.php';
                }
            } catch (CoreException $e) {
                http_response_code($e->getStatusCode());
                echo json_encode([
                    'error' => $e->getMessage()
                ]);
            } catch (Throwable $e) {
                http_response_code(500);
                echo json_encode([
                    'error' => 'Internal server error',
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
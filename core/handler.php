<?php

    //load file env variables
    function env($key = null, $default = null) {
        static $loaded = false;

        if (!$loaded) {
            $path = __DIR__ . '../../.env';
            if (file_exists($path)) {
                foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                    $line = trim($line);
                    if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
                        continue;
                    }

                    [$envKey, $envValue] = explode('=', $line, 2);
                    $envKey = trim($envKey);
                    //$envValue = trim($envValue, "\"'");
                    $envValue = trim($envValue, "\t\n\r\0\x0B\"'");
                    putenv("$envKey=$envValue");
                    $_ENV[$envKey] = $envValue;
                    $_SERVER[$envKey] = $envValue;
                }
            }
            $loaded = true;
        }

        if ($key === null) return null;

        return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?? $default;
    }

    function config($key, $default = null) {
        $segments = explode('.', $key);
        $data = $GLOBALS['config'] ?? [];

        foreach ($segments as $segment) {
            if (isset($data[$segment])) {
                $data = $data[$segment];
            } else {
                return $default;
            }
        }
        return $data;
    }

    use core\Container;

    if (!function_exists('app')) {
        function app($name = null) {
            if (is_null($name)) {
                return core\Container::all();
            }

            return core\Container::make($name);
        }
    }
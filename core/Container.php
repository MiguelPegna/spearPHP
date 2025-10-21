<?php
    //dependencies
    namespace core;

    class Container {
        protected static array $instances = [];

        public static function bind(string $name, $concrete): void {
            static::$instances[$name] = $concrete;
        }

        public static function make(string $name) {
            return static::$instances[$name] ?? null;
        }

        public static function all(): array {
            return static::$instances;
        }

        public static function resolve(string $name) {
            // Si ya está instanciado, lo devuelve
            if (isset(static::$instances[$name])) {
                return static::$instances[$name];
            }
        
            // Si no existe la clase, regresamos null
            if (!class_exists($name)) {
                return null;
            }
        
            // Refleja la clase para inspeccionar su constructor
            $reflection = new \ReflectionClass($name);
        
            // Si no tiene constructor o no necesita dependencias, la instanciamos directo
            if (!$reflection->getConstructor()) {
                return $reflection->newInstance();
            }
        
            $params = $reflection->getConstructor()->getParameters();
            $dependencies = [];
        
            foreach ($params as $param) {
                $paramClass = $param->getType()?->getName();
        
                // Si no tiene tipo o no es clase, se detiene
                if (!$paramClass || !class_exists($paramClass)) {
                    throw new \Exception("Cannot resolve class dependency {$param->getName()}");
                }
        
                // Recursivamente resolvemos dependencias
                $dependencies[] = static::resolve($paramClass);
            }
        
            return $reflection->newInstanceArgs($dependencies);
        }
        
    }
<?php

    namespace core\Helpers;

    class DebugHelper{
            //muestra información formateada
        public static function dp($data){
            $format = print_r('<pre');
            $format .= print_r($data);
            $format .= print_r('<pre');
            return $format;
        }

        // Verificar si está en CLI
        public static function isCli(): bool {
            return php_sapi_name() === 'cli';
        }

        // Generar UUID v4 simple
        public static function uuid(): string {
            $data = random_bytes(16);
            $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
            $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

    }
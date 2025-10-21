<?php

    namespace core\Helpers;

    class FileHelper {
        /** 
        * files and dir
        */
        /**
        * #Sube archivo al servidor
        * @var array $file info file
        * @var string $destination nombre que tendra el archivo
        * @var string $name nombre que tendra el archivo
        * @return filename $url devuelve la url del archivo en el servidor
        */
        public static function upload(array $file, string $destination, ?string $name = null): ?string {
            if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) return null;

            if (!file_exists($destination)) mkdir($destination, 0755, true);

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $name ? $name . '.' . $ext : uniqid() . '.' . $ext;
            $target = rtrim($destination, '/') . '/' . $filename;

            if (move_uploaded_file($file['tmp_name'], $target)) {
                return $filename;
            }

            return null;
        }

        /**
        * #borra archivo al servidor
        * @var string $path ubicacion del archivo a borrar
        * @return bool return true o false
        */
        public static function delete(string $path){
            //solo se borra la img si es diferente de la img por defecto
            return file_exists($path) ? unlink($path) : false;
        }

        // Verificar existencia de archivo
        public static function exists(string $path): bool {
            return file_exists($path);
        }

        // Crear directorio si no existe
        public static function createDirectory(string $path): bool {
            if (!file_exists($path)) {
                return mkdir($path, 0755, true);
            }
            return true;
        }

        // Obtener extensión de archivo
        public static function extension(string $filename): string {
            return pathinfo($filename, PATHINFO_EXTENSION);
        }

        // Obtener tamaño de archivo en KB
        public static function size(string $file): float {
            return file_exists($file) ? filesize($file) / 1024 : 0;
        }
    }

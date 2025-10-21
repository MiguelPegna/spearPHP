<?php

    namespace core\Helpers;

    class SecurityHelper{

         /*
        * Seguridad
        */

        /**
        * #hashear password
        * @var string $password cadena de texto
        * @return string $hashedPass devuelve un string hasheado para guardar en la db
        */
        public static function hashPassword($password){
            //codificar la contraseña
            return password_hash($password, PASSWORD_BCRYPT);
        }

        /**
        * #verificar el password
        * @var string $password contraseña a comparar
        * @var string $passDB password del user en la DB
        * @return bool $checkPass devuelve true si los password coinciden y false si no
        */
        public static function verifyPassword($password, $hash){
            return password_verify($password, $hash);
        }

        //genera un password de 10 caracteres
        /**
        * #Limpia la data recibida de posibles cadenas maliciosas y Elimina excesos de espacios entre palabras
        * @var int $length longitud que tendra el pass por default es 10
        * @return string $pass devuelve un password random
        */
        public static function createPassword($length = 12) {
            $password ='';
            $longPass = $length;
            $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789-_#*@';
            $stringLength = strlen($string);

            for($i=1; $i<=$longPass; $i++){
                $pos = rand(0, $stringLength-1);
                $password .= substr($string, $pos,1);
            }
            return $password;
        }

    }
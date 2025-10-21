<?php

    namespace core\Helpers;

    class DataHelper{
        //funcion convertir string de DB a array
        public static function toArr($char, $string){
            $string= str_replace("[", "", $string);
            $array = explode("'" .$char. "'", $string);
            return $array;
        }

        //funcion que quita caracteres especiales para url
        public static function removeChars($cadena){
            //Reemplazamos la A y a
            $cadena = str_replace(
                array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
                array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
                $cadena
            );
        
            //Reemplazamos la E y e
            $cadena = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena );
        
            //Reemplazamos la I y i
            $cadena = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena );
        
            //Reemplazamos la O y o
            $cadena = str_replace(
            array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $cadena );
        
            //Reemplazamos la U y u
            $cadena = str_replace(
            array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $cadena );
        
            //Reemplazamos la N, n, C y c
            $cadena = str_replace(
            array('Ñ', 'ñ', 'Ç', 'ç',',','.',';',':','&'),
            array('N', 'n', 'C', 'c','','','','','y'),
            $cadena
            );
            return $cadena;

        }

        //formato para valores moenetarios
        public static function formatMoney($cantidad){
            $cantidad = number_format($cantidad, 2, config('currency.spd'), config('currency.spm'));
            return $cantidad;
        }
        
    }
<?php

    namespace core\Helpers;

    class DateHelper {
        /**
         * ----------------------------------------
         * Fechas y tiempo
         * ----------------------------------------
         */

        public static function now(): string {
            return date('Y-m-d H:i:s');
        }

        public static function formatDate(string $date, string $format = 'Y-m-d H:i:s'): string {
            return date($format, strtotime($date));
        }

        public static function diffInMinutes(string $start, string $end): int {
            return (int)((strtotime($end) - strtotime($start)) / 60);
        }


        public static function meses(){
            $meses =['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            return $meses;
        }

    }

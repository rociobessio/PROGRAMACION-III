<?php


    class Validaciones{

        /**
        * Funcion privada que me permitira validar si 
        * el tipo ingresado es valido.
        */
        public static function validarTipo($value){
            $tipos = ["AGUA","CREMA"];
            if(in_array(strtoupper($value),$tipos))
                return true;
            else
                return false;
        }

        /**
        * Funcion privada que me permitira validar si 
        * el vaso ingresado es valido.
        */
        public static function validarVaso($value){
            $tipos = ["CUCURUCHO","PLASTICO"];
            if(in_array(strtoupper($value),$tipos))
                return true;
            else
                return false;
        }

        /**
         * Para reutilizar y no tener que repetir
         * acciones, se crea esta funcion para poder
         * buscar un producto dentro del array.
         * 
         * Si existe retorna el helado, sino null.
         */
        public static function BuscarHelado($helados,$sabor,$tipo,$helado = null){
            foreach ($helados as $helado) {
                if($helado['sabor'] === $sabor && $helado['tipo'] === $tipo){
                    return $helado;
                }
           }
           return null;
        }

        /**
         * Retorna true si hay stock suficiente para poder
         * acutalizar su stock sino retorna false.
         */
        public static function verificarStock($helado,$cantidad){
            return $helado['stock'] >= $cantidad;
        }

        /**
         * Funcion generica para traerme un
         * array de productos de un archivo
         * JSON.
         */
        public static function ObtenerArrayProducto($jsonFile){
            $arrayObjetos = array();

            if(file_exists($jsonFile)){
                $contenido = file_get_contents($jsonFile);
                $arrayObjetos = json_decode($contenido,true);
            }
            return $arrayObjetos;
        }

    }
<?php

    class Hamburguesa{
                /**
         * Me permitirá validar el aderezo.
         */
        public static function ValidarAderezo($aderezo){
            $aderezos = ["MOSTAZA","MAYONESA", "KETCHUP"];
                if(in_array(strtoupper($aderezo),$aderezos))
                    return true;
                else
                    return false;
        }

        /**
         * Me permitirá validar el tipo de hamburguesa.
         */
        public static function ValidarTipo($tipo){
            $tipos = ["SIMPLE","DOBLE"];
                if(in_array(strtoupper($tipo),$tipos))
                    return true;
                else
                    return false;
        }

        /**
         * Devuelve true o false
         * si hay stock del producto.
         */
        public static function verificarStock($hamburguesa,$cantidad){
            return $hamburguesa['cantidad'] >= $cantidad;
        }

        /**
        * Funcion generica para traerme un
        * array de productos de un archivo
        * JSON.
        */
        public static function ObtenerHamburguesas($jsonFile){
            $arrayObjetos = array();

            if(file_exists($jsonFile)){
                $contenido = file_get_contents($jsonFile);
                $arrayObjetos = json_decode($contenido,true);
            }
            return $arrayObjetos;
        }

        /**
        * Me permitira saber si existe o no
        * una hamburguesa.
        */
        public static function BuscarHamburguesa($hamburguesas,$nombre,$tipo){
            if($hamburguesas !== null){
                foreach ($hamburguesas as $hamburguesa) {
                     //var_dump($hamburguesa);
                    if($hamburguesa['nombre'] === $nombre && $hamburguesa['tipo'] === $tipo){
                        return $hamburguesa;
                    }
               }
            }
          return null;
       }

       /**
        * Me permitira actualizar la hamburguesa en el json
        */
        public static function ActualizarHamburguesa(&$hamburguesas, $hamburguesaExistente, $json_file)
        {
            foreach ($hamburguesas as &$hamburguesa) {
                if ($hamburguesa["id"] == $hamburguesaExistente["id"]) {
                    $hamburguesa = $hamburguesaExistente;
                    break;
                }
            }

            $hamburguesasJSON = json_encode($hamburguesas, JSON_PRETTY_PRINT);
            file_put_contents($json_file, $hamburguesasJSON);
        }
    }
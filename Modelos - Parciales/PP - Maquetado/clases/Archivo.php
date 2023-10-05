<?php

    class Archivo{

        /**
         * funcion generalizada para poder obtener
         * un array de objetos de un json.
         * 
         * @param jsonFile el path del archivo.
         * @return array el array de los objetos.
         */
        public static function ObtenerArray($jsonFile){
            $arrayObjetos = array();

            if(file_exists($jsonFile)){
                $contenido = file_get_contents($jsonFile);
                $arrayObjetos = json_decode($contenido,true);
            }
            return $arrayObjetos;
        }
    }
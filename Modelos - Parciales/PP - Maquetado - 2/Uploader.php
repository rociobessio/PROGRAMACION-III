<?php

    class Uploader{
        private $_directorio;

        public function __construct($directorio)
        {
            $this->_directorio = $directorio;
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }
        }

        public function guardarImagen($tempFile, $newFileName)
        {
            $destino = $this->_directorio . $newFileName; 
            return move_uploaded_file($tempFile, $destino);
        } 
        
        public static function crearPathImagenVenta($email,$sabor,$tipo){
            $posicionArroba = strpos($email, "@");
            $stringFinal = substr($email, 0, $posicionArroba);
            $nombreImagen =  $tipo . '_' . $sabor . '_'
                . $stringFinal . '_' . (new DateTime('now'))->format('Y-m-d') . '.jpg'; 
            return $nombreImagen;
        }

        public function moverImagenABackUp($rutaImagen, $directorioRespaldo, $nuevoNombre) {
            // Genera la ruta completa en el directorio de respaldo
            $rutaRespaldo = $directorioRespaldo . $nuevoNombre;
    
            if (file_exists($rutaImagen)) {
                // Mueve la imagen a la carpeta de respaldo 
                return rename($rutaImagen, $rutaRespaldo);
            }
    
            return false;  // La imagen no existe
        }
    }
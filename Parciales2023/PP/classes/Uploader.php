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
        
        public function moverImagenABackUp($rutaImagen, $directorioRespaldo, $nuevoNombre) {
            //--Formo la ruta completa del path backup
            $rutaRespaldo = $directorioRespaldo . $nuevoNombre;
    
            if (file_exists($rutaImagen)) {
                //-->Muevo la imagen 
                return rename($rutaImagen, $rutaRespaldo);
            }
    
            return false;//-->No existe
        }
    }
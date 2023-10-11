<?php
/**
 * Esta clase me permitira manejar el uso de imagenes
 */

 class UploadImage{
//********************************************* ATRIBUTOS *********************************************
    private $_directorioAGuardar;
    private $_fileExtension;
    private $_newFileName;
    private $_pathToSaveImage;
//********************************************* SETTERS *********************************************
    public function setDirectorioAGuardar($dirToSave){
        $this->_directorioAGuardar = $dirToSave;
    }
    public function setFileExtension($fileExtension){
        $this->_fileExtension = $fileExtension;
    }
    public function setNewFileName($newFileName){
        $this->_newFileName = $newFileName;
    }
    public function setPathParaGuardarImagen(){
        $this->_pathToSaveImage = $this->getDirectoryAGuardar().$this->getNewFileName().'.'.$this->getFileExtension();
    }
//********************************************* GETTERS *********************************************
    public function getDirectoryAGuardar(){
        return $this->_directorioAGuardar;
    }

    public function getFileExtension(){
        return $this->_fileExtension;
    }

    public function getNewFileName(){
        return $this->_newFileName;
    }

    public function getPathToSaveImage(){
        return $this->_pathToSaveImage;
    }
//********************************************* CONSTRUCTOR *********************************************
    public function __construct($directorioAGuardar,$venta,$array)
    {
        self::CrearDirectorioSiNoExiste($directorioAGuardar);
        $this->setDirectorioAGuardar($directorioAGuardar);
        $this->GuardarArchivoEnDirectorio($venta, $array);
    }
//********************************************* FUNCTIONES *********************************************
 }
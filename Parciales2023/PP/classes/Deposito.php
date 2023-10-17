<?php

    require_once "./classes/Cuenta.php";
    require_once "./Uploader.php";

    class Deposito{
//********************************************* ATRIBUTOS *********************************************
        public $_id;
        public $_numeroCuenta;
        public $_tipoCuenta;
        public $_moneda;
        public $_importe;
        public $_fechaDeposito;
//********************************************* GETTERS *********************************************
        public function getID(){
            return $this->_id;
        }
        public function getNumeroCuenta(){
            return $this->_numeroCuenta;
        }
        public function getTipoCuenta(){
            return $this->_tipoCuenta;
        }
        public function getMoneda(){
            return $this->_moneda;
        }
        public function getImporte(){
            return $this->_importe;
        }
        public function getFechaDeposito(){
            return $this->_fechaDeposito;
        }
//********************************************* SETTERS *********************************************
        public function setID($id){
            if (isset($id) && is_numeric($id)){
                $this->_id = $id;
            }
        }
        public function setNumeroCuenta($numero){
            if (isset($numero) && is_numeric($numero)){
                $this->_numeroCuenta = $numero;
            }
        }
        public function setTipoCuenta($tipo){
            if(isset($tipo) && !empty($tipo)) {
                $this->_tipoCuenta = $tipo;
            }
        }
        public function setMoneda($moneda){
            if(isset($moneda) && !empty($moneda)) {
                $this->_moneda = $moneda;
            }
        }
        public function setImporte($importe){
            if (isset($importe) && is_float($importe)){
                $this->_importe= $importe;
            }
        }
        public function setFechaDeposito($fecha){
            if(isset($fecha) && !empty($fecha)) {
                $this->_fechaDeposito = $fecha;
            }
        }
//********************************************* CONSTRUCTOR *********************************************
        public function __construct($id,$numeroCuenta,$tipoCuenta,$moneda,$importe,$fecha)
        {
            $this->setID($id);
            $this->setNumeroCuenta($numeroCuenta);
            $this->setTipoCuenta($tipoCuenta);
            $this->setMoneda($moneda);
            $this->setImporte($importe);
            $this->setFechaDeposito($fecha);
        }
//********************************************* FUNCIONES *********************************************

        /**
         * Me permitira generar un nuevo deposito
         */
        public static function generarDeposito($cuenta,$cuentas,$importe,$moneda,$imagen){
            $jsonFileCuentas = './archivos/banco.json';
            $jsonFileDepositos = './archivos/depositos.json';
            $archivoGuardar = new Uploader('./ImagenesDeDepositos/2023/');
            $depositos = Deposito::leerJSON($jsonFileDepositos);

            if($cuenta !== null){
                $cuenta->setSaldo( $cuenta->getSaldo() + $importe);
                if(Cuenta::actualizarCuenta($cuentas,$cuenta,$jsonFileCuentas)){
                    $nuevoDeposito = new Deposito(
                        empty($depositos) ? 1 : (count($depositos) + 1),
                        $cuenta->getID(),
                        $cuenta->getTipoCuenta(),
                        $moneda,
                        floatval($importe),
                        (new DateTime('now'))->format('Y-m-d')
                    );

                    $depositos[] = $nuevoDeposito;
                    // var_dump($depositos);
                    if($archivoGuardar){//-->Intento guardar la imagen
                        $nombreImagen = $cuenta->getTipoCuenta(). '_' . $cuenta->getID() .'_' . $nuevoDeposito->getID() . '.jpg';
                        $archivoGuardar->guardarImagen($imagen['tmp_name'],$nombreImagen);
                    }
                    else{
                        echo json_encode(['WARNING' => 'No se ha podido guardar la imagen del deposito!<br>']);
                    }
                    return self::guardarJSON($depositos,$jsonFileDepositos);
                }
                echo 'Ocurrio un error al querer actualizar el importe!<br>';
                return false;
            }
            else{
                echo 'La cuenta no existe!<br>';
                return false;
            }
        }

        
        /**
         * Me permitira leer un archivo json de depositos
         * bancarias.
         * @param string $jsonFile el path del archivo
         * 
         * @return array retornara el array de depositos
         * leidas del json.
         */
        public static function leerJSON($jsonFile){
            $depositos = array();
            if(file_exists($jsonFile)){
                $archivo = fopen($jsonFile,"r");
                if($archivo){
                    $fileSize = filesize($jsonFile);
                    if($fileSize > 0){
                        $json = fread($archivo,$fileSize);
                        $depositosJSON = json_decode($json,true); 
                        foreach($depositosJSON as $deposito){
                            array_push($depositos,new Deposito(
                                intval($deposito["_id"]),
                                intval($deposito["_numeroCuenta"]),
                                $deposito["_tipoCuenta"],
                                $deposito["_moneda"],
                                floatval($deposito["_importe"]),
                                $deposito["_fechaDeposito"],
                            ));
                        }
                    }
                    fclose($archivo);
                }
            }
            return $depositos;
        }

        /**
         * Me permitira guardar un array de depositos
         * en el archiov correspondiente.
         */
        public static function guardarJSON($depositos,$jsonFile){
            $success = false;
            try {
                $file = fopen($jsonFile, "w");
                if ($file) {
                    $json = json_encode($depositos, JSON_PRETTY_PRINT); 
                    fwrite($file, $json);
                    $success = true;
                }
            } catch (\Throwable $th) {
                echo "Error al guardar el archivo";
            } finally {
                fclose($file);
                return $success;
            }
        }
    }
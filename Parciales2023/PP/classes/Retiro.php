<?php

    require_once "./classes/Cuenta.php";

    class Retiro{
//********************************************* ATRIBUTOS *********************************************
        public $_id;
        public $_numeroCuenta;
        public $_tipoCuenta;
        public $_monedaCuenta;
        public $_importeRetiro;
        public $_emailUsuario;
        public $_fechaExtraccion; 
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
        public function getMonedaCuenta(){
            return $this->_monedaCuenta;
        }
        public function getImporteRetiro(){
            return $this->_importeRetiro;
        }
        public function getEmailUsuario(){
            return $this->_emailUsuario;
        }
        public function getFechaExtraccion(){
            return $this->_fechaExtraccion;
        } 
//********************************************* SETTERS *********************************************
        public function setID($id){
            if (isset($id) && is_numeric($id)){
                $this->_id = $id;
            }
        }
        public function setNumeroCuenta($nroCuenta){
            if (isset($nroCuenta) && is_numeric($nroCuenta)){
                $this->_numeroCuenta = $nroCuenta;
            }
        }
        public function setTipoCuenta($tipoCuenta){
            if(isset($tipoCuenta) && !empty($tipoCuenta)) {
                $this->_tipoCuenta = $tipoCuenta;
            }
        }
        public function setMonedaCuenta($moneda){
            if(isset($moneda) && !empty($moneda)) {
                $this->_monedaCuenta = $moneda;
            }
        }
        public function setEmailUsuario($email){
            if(isset($email) && !empty($email)) {
                $this->_emailUsuario = $email;
            }
        }
        public function setImporteRetiro($importe){
            if(isset($importe) && is_float($importe)) {
                $this->_importeRetiro = $importe;
            }
        }
        public function setFechaExtraccion($fechaRetiro){
            if(isset($fechaRetiro) && !empty($fechaRetiro)) {
                $this->_fechaExtraccion = $fechaRetiro;
            }
        } 
//********************************************* CONSTRUCTOR *********************************************
        /**
         * Constructor que me permitira generar una instancia parametrizada
         * de la clase Retiro.
         * @param int $id
         * @param int $nroCuenta
         * @param string $tipoCuenta
         * @param string $moneda
         * @param string $email
         * @param float $importe a retirar
         * @param string $fechaRetiro
         * @param float $montoTotal el monto total final
         */
        public function __construct($id,$nroCuenta,$tipoCuenta,$moneda,$email,$importe,$fechaRetiro)
        {
            $this->setID($id);
            $this->setNumeroCuenta($nroCuenta);
            $this->setTipoCuenta($tipoCuenta);
            $this->setMonedaCuenta($moneda);
            $this->setEmailUsuario($email);
            $this->setImporteRetiro($importe);
            $this->setFechaExtraccion($fechaRetiro); 
        }
//********************************************* CONSTRUCTOR *********************************************
        /**
         * Me permitira generar una extraccion de una cuenta 
         * verificando si existe la cuenta mediante su nro de cuenta,
         * tipo de cuenta y coincidencia de moneda en cuenta. Si
         * coincide verificara que le saldo disponible sea 
         * mayor al importe a retirar. 
         * Si lo es actualiza el saldo de la cuenta y genera 
         * un nuevo retiro guardandolo en un archivo json.
         * 
         * @param Cuenta $cuenta la cuenta 
         * @param float $importeRetiro el importe a extraer de la cuenta.
         * @param array $cuentas el array de cuentas
         * @param string $moneda la moneda de la cuenta.
         * 
         * @return bool true si se pudo realizar el retiro
         * correctamente, false sino.
         */
        public static function generarRetiro($cuenta,$importeRetiro,$cuentas){ 
            $jsonFileRetiros = './archivos/retiros.json';
            $retiros = Retiro::leerJSON($jsonFileRetiros); 
            if($cuenta !== null){ 
                if($cuenta->verificarSaldo($importeRetiro)){
                    $cuenta->setSaldo($cuenta->getSaldo() - $importeRetiro);
                    //-->Intento actualizar la cuenta
                    if(Cuenta::actualizarCuenta($cuentas,$cuenta,'./archivos/banco.json')){

                        $retiros[] = new Retiro(empty($retiros) ? 1 : (count($retiros) + 1),
                        $cuenta->getID(),$cuenta->getTipoCuenta(),$cuenta->getMoneda(),
                        $cuenta->getEmail(),$importeRetiro,(new DateTime('now'))->format('Y-m-d'));
 
                        return self::guardarJSON($retiros,$jsonFileRetiros);
                    }
                    else{
                        echo 'No se ha podido actualizar la cuenta!';
                    }
                }
                else{
                    echo 'La cuenta no tiene el saldo suficiente para realizar la extraccion!<br>'; 
                }
            }
            else{
                echo 'La cuenta solicitada no existe!<br>'; 
            }
            return false;
        }

        /**
         * Me permitira buscar si hay coincidencia 
         * con el id del numero de retiro.
         */
        public static function buscarRetiro($retiros,$nroRetiro){
            foreach ($retiros as $retiro) {
                if($retiro->getID() === $nroRetiro){
                    return $retiro;
                }
            }
            return null;
        }
        
        /**
         * Me permite actualizar un instancia de 
         * la clase Retiro mediante coincidencia
         * de ids. Guardandola en el archivo json.
         * 
         * @param array $retiros
         * @return bool true si pudo guardar
         * false sino.
         */
        public function actualizarRetiro(&$retiros){
            foreach ($retiros as &$retiro) { 
                if ($retiro->getID() == $this->getID()) {
                    $retiro = $this; 
                    break;
                }
            }
            return self::guardarJSON($retiros,'./archivos/retiros.json');
        } 

        /**
         * Me permitira leer un archivo json de retiros
         * bancarios.
         * @param string $jsonFile el path del archivo
         * 
         * @return array retornara el array de retiros
         * leidas del json.
         */
        public static function leerJSON($jsonFile){
            $retiros = array();
            if(file_exists($jsonFile)){
                $archivo = fopen($jsonFile,"r");
                if($archivo){
                    $fileSize = filesize($jsonFile);
                    if($fileSize > 0){
                        $json = fread($archivo,$fileSize);
                        $retirosJSON = json_decode($json,true); 
                        foreach($retirosJSON as $retiro){
                            array_push($retiros,new Retiro(
                                intval($retiro["_id"]),
                                intval($retiro["_numeroCuenta"]),
                                $retiro["_tipoCuenta"],
                                $retiro["_monedaCuenta"], 
                                $retiro["_emailUsuario"],
                                floatval($retiro["_importeRetiro"]),
                                $retiro["_fechaExtraccion"],
                                // floatval($retiro["_montoTotal"]), 
                            ));
                        }
                    }
                    fclose($archivo);
                }
            }
            return $retiros;
        }

        /**
         * Me permite verificar si el importe de
         * la instancia es mayor al valor que recibo.
         * 
         * @param float $valor el valor a validar
         * @return bool true si se cumple, false sino.
         */
        public function verificarImporte($valor){
            return $this->getImporteRetiro() >= $valor;
        }
        
        /**
         * Me permitira guardar un array de retiros
         * en el archivo jsonIndicado.
         * 
         * @param array $cuentas el array de retiros.
         * @param string $jsonFilename el path del archivo
         * 
         * @return bool true si pudo, false sino.
         */
        public static function guardarJSON($retiros, $jsonFilename){
            $success = false;
            try {
                $file = fopen($jsonFilename, "w");
                if ($file) {
                    $json = json_encode($retiros, JSON_PRETTY_PRINT);
                    fwrite($file, $json);
                    $success = true;
                }
            } catch (\Throwable $th) {
                echo "Error al guardar el archivo!";
            } finally {
                fclose($file);
                return $success;
            }
        }
    }
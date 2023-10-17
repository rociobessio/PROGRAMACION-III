<?php


    class Cuenta{
//********************************************* ATRIBUTOS *********************************************
        public $_id;
        public $_nombre;
        public $_apellido;
        public $_tipoDocumento;
        public $_numeroDocumento;
        public $_email;
        public $_tipoCuenta;
        public $_moneda;
        public $_saldo;         
//********************************************* GETTERS *********************************************
        public function getID(){
            return $this->_id;
        }
        public function getNombre(){
            return $this->_nombre;
        }
        public function getApellido(){
            return $this->_apellido;
        }
        public function getTipoDocumento(){
            return $this->_tipoDocumento;
        }
        public function getNumeroDocumento(){
            return $this->_numeroDocumento;
        }
        public function getEmail(){
            return $this->_email;
        }
        public function getTipoCuenta(){
            return $this->_tipoCuenta;
        }
        public function getSaldo(){
            return $this->_saldo;
        }
        public function getMoneda(){
            return $this->_moneda;
        } 
//********************************************* SETTERS *********************************************
        public function setID($id){
            if (isset($id) && is_numeric($id)){
                $this->_id = $id;
            }
        }
        public function setNombre($nombre){
            if(isset($nombre) && !empty($nombre)) {
                $this->_nombre = $nombre;
            }
        }
        public function setApellido($apellido){
            if(isset($apellido) && !empty($apellido)) {
                $this->_apellido = $apellido;
            }
        }
        public function setTipoDocumento($tipoDocumento){
            if(isset($tipoDocumento) && !empty($tipoDocumento)) {
                $this->_tipoDocumento = $tipoDocumento;
            }
        } 
        public function setNumeroDocumento($numeroDocumento){
            if(isset($numeroDocumento) && !empty($numeroDocumento)) {
                $this->_numeroDocumento = $numeroDocumento;
            }
        }
        public function setEmail($email){
            if(isset($email) && !empty($email)) {
                $this->_email = $email;
            }
        }
        public function setTipoCuenta($tipoCuenta){
            if(isset($tipoCuenta) && !empty($tipoCuenta)) {
                $this->_tipoCuenta = $tipoCuenta;
            }
        }
        public function setMoneda($moneda){
            if(isset($moneda) && !empty($moneda)) {
                $this->_moneda = $moneda;
            }
        }
        public function setSaldo($saldo){
            if (isset($saldo) && is_float($saldo)){
                $this->_saldo = $saldo;
            }
        }
//********************************************* CONSTRUCTOR *********************************************
        /**
         * Constructor que me permitira crear una instancia parametrizada
         * de la clase Cuenta.
         * 
         * @param int $id la id de la cuenta.
         * @param string $nombre nombre de la cuenta.
         * @param string $apellido apellido de la cuenta.
         * @param string $tipoDocumento tipo de documento valido.
         * @param string $numeroDocumento numero de documento valido.
         * @param string $mail el email 
         * @param string $tipoCuenta el tipo de la cuenta
         * @param float $saldo el saldo de la cuenta.
         */
        public function __construct($id,$nombre,$apellido,$tipoDocumento,$numeroDocumento,$mail,$tipoCuenta,$moneda,$saldo = 0)
        {
            $this->setID($id);
            $this->setNombre($nombre);
            $this->setApellido($apellido);
            $this->setTipoDocumento($tipoDocumento);
            $this->setNumeroDocumento($numeroDocumento);
            $this->setEmail($mail);
            $this->setTipoCuenta($tipoCuenta);
            $this->setMoneda($moneda);
            $this->setSaldo($saldo); 
        }
//********************************************* FUNCIONES *********************************************
        /**
         * Me permitira validar si el tipo de 
         * cuenta es Cuenta Corriente o Caja
         * de Ahorro.
         * @param string $tipo a verificar
         * 
         * @return bool true si es valido,
         * false sino.
         */
        public static function validarTipoCuenta($tipo){
            $tipos = ["CA","CC"];
            if(in_array(strtoupper($tipo),$tipos))
                return true;
            else
                return false;
        }

        public static function validarMoneda($moneda){
            $tipos = ["$","USS"];
            if(in_array(strtoupper($moneda),$tipos))
                return true;
            else
                return false;
        }

        /**
         * Me permite validar un tipo de documeto
         * argentino:
         * Documento Nacional de Identidad -D.N.I. 
         * Libreta Cívica - L.C. 
         * Libreta de Enrolamiento - L.E. 
         * Cédula de Identidad -C.I.
         */
        public static function validarTipoDocumento($tipo){
            $tipos = ["DNI","LC","LE","CI"];
            if(in_array(strtoupper($tipo),$tipos))
                return true;
            else
                return false;
        }

        /**
         * Me permite verificar si la cuenta que recibo es null o 
         * no. Si es null es una nueva cuenta a registrar dando su 
         * alta, sino es null se actualizará su saldo.
         * 
         * @param Cuenta $cuenta la cuenta que se buscara para
         * saber si ya existe o no en el sistema.
         * @param array $cuentas el array de cuentas.
         * @param float $saldo el saldo.
         * @param string $jsonFile el path del archivo de cuentas.json
         * 
         * @return bool true si pudo actualizar o crear la cuenta,
         * false sino.
         */
        public static function cargarCuenta($cuenta,$cuentas,$saldo,$jsonFile){
            $cuentaBuscar  = $cuenta->buscarCuenta($cuentas,$cuenta->getNombre(),$cuenta->getTipoCuenta());
            if($cuentaBuscar !== null){//-->Existe
                $cuentaBuscar->setSaldo( $cuentaBuscar->getSaldo() + $saldo);
                // var_dump($cuenta->getSaldo());
                return self::actualizarCuenta($cuentas,$cuentaBuscar,$jsonFile);
            }
            else{//-->false, no existe
                $nuevaCuenta = $cuenta; 
                $cuentas[] = $nuevaCuenta;
                echo 'Creando cuenta...!<br>';
                return self::guardarJSON($cuentas,$jsonFile);
            }
            return false;
        }

        

        /**
         * Actualiza una cuenta en el array 
         * mediante la coincidencia de ids.
         * 
         * @param array el array de cuentas
         * @param Cuenta la cuenta existente
         * @param string $jsonFile el path
         * donde se guardara la actualizacion.
         * 
         * @return bool true si pudo guardar
         * bien el archivo, false sino.
         */
        public static function actualizarCuenta(&$cuentas, $cuentaExistente, $jsonFile){
            foreach ($cuentas as &$cuenta) { 
                if ($cuenta->getID() == $cuentaExistente->getID()) {
                    $cuenta = $cuentaExistente;
                    echo 'Actualizando cuenta...!<br>';
                    // var_dump($cuenta);
                    break;
                }
            }
            return self::guardarJSON($cuentas,$jsonFile);
        } 

        /**
         * Me permitira buscar si existe o no una cuenta dentro
         * del array de cuentas.
         * 
         * @param array $cuentas el array de cuentas.
         * @param string $nombre del cuenta
         * @param string $tipoCuenta el tipo de la cuenta.
         * 
         * @return Cuenta||null retornara la cuenta si existe,
         * null sino.
         */
        public function buscarCuenta($cuentas,$nombre,$tipoCuenta){ 
                foreach ($cuentas as $cuenta) {
                    // var_dump($cuenta);
                    if($cuenta->getNombre() === $nombre && $cuenta->getTipoCuenta() === $tipoCuenta){
                        return $cuenta;
                } 
            }
            return null;
        }

        public static function buscarPorNumeroCuenta($cuentas,$nroCuenta,$tipoCuenta){
            foreach ($cuentas as $cuenta) {
                if($cuenta->getID() === $nroCuenta && $cuenta->getTipoCuenta() === $tipoCuenta){
                    return $cuenta;
                }
            }
            return null;
        }

        /**
         * Me permitira retornar el ultimo id del array.
         * @param array $cuentas el array de cuentas
         * 
         * @return int $ultimoID el ultimo ID.
         */
        public static function obtenerUltimoID($cuentas){
            $ultimoID = 0;
            foreach ($cuentas as $cuenta) {
                if ($cuenta->getID() > $ultimoID) {
                    $ultimoID = $cuenta->getID();
                }
            } 
            return $ultimoID;
        }

        /**
         * Me permitira verificar si existe una cuenta
         * mediante el numero de cuenta y el tipo
         * buscado.
         * 
         * @param array $cuentas el array de cuentas
         * @param int $numeroCuenta el numero de cuenta
         * @param string $tipo el tipo de cuenta
         * 
         * @return string un string con el resultado 
         * final de la busqueda.
         */
        public static function buscarCuentaPor($cuentas,$numeroCuenta,$tipo){
            $msj = "";

            $cuentasConTipo = [];
            $cuentasConNro = [];
            foreach($cuentas as $cuenta){
                if($cuenta->getTipoCuenta() === $tipo && $cuenta->getID() === $numeroCuenta){
                    return 'Si hay cuentas con tipo de cuenta: ' . $tipo . ' y numero: ' . $numeroCuenta;
                }
                if($cuenta->getTipoCuenta() === $tipo){
                    $cuentasConTipo[] = $cuenta;
                }
                if($cuenta->getID() === $numeroCuenta)
                { $cuentasConNro = $cuenta;}
            }

            if(!empty($cuentasConTipo) && !empty($cuentasConNro)){
                $msj = 'Si hay cuenta con tipo: ' . $tipo . ' y numero: ' . $numeroCuenta;
            }
            elseif (!empty($cuentasConNro)){
                $msj = 'Solo hay cuenta con numero: '. $numeroCuenta ;
            }
            elseif (!empty($cuentasConTipo)){
                $msj = 'Solo hay cuenta con tipo: '. $tipo ;
            }
            return $msj;
        }

        /**
         * Me permitira leer un archivo json de cuentas
         * bancarias.
         * @param string $jsonFile el path del archivo
         * 
         * @return array retornara el array de cuentas
         * leidas del json.
         */
        public static function leerJSON($jsonFile){
            $cuentas = array();
            if(file_exists($jsonFile)){
                $archivo = fopen($jsonFile,"r");
                if($archivo){
                    $fileSize = filesize($jsonFile);
                    if($fileSize > 0){
                        $json = fread($archivo,$fileSize);
                        $cuentasJSON = json_decode($json,true);
                        // var_dump($cuentasJSON);
                        foreach($cuentasJSON as $cuenta){
                            array_push($cuentas,new Cuenta(
                                intval($cuenta["_id"]),
                                $cuenta["_nombre"],
                                $cuenta["_apellido"],
                                $cuenta["_tipoDocumento"],
                                $cuenta["_numeroDocumento"],
                                $cuenta["_email"],
                                $cuenta["_tipoCuenta"],
                                $cuenta["_moneda"], 
                                floatval($cuenta["_saldo"]),
                            ));
                        }
                    }
                    fclose($archivo);
                }
            }
            return $cuentas;
        }

        /**
         * Me permitira guardar un array de cuentas
         * en el archivo jsonIndicado.
         * 
         * @param array $cuentas el array de cuentas.
         * @param string $jsonFilename el path del archivo
         * 
         * @return bool true si pudo, false sino.
         */
        public static function guardarJSON($cuentas, $jsonFilename){
            $success = false;
            try {
                $file = fopen($jsonFilename, "w");
                if ($file) {
                    $json = json_encode($cuentas, JSON_PRETTY_PRINT);
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
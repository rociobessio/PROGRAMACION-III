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
            if(isset($tipoDocumento) && !empty($tipoDocumento) && self::validarTipoDocumento($tipoDocumento)) {
                $this->_tipoDocumento = $tipoDocumento;
            }
            else{
                echo 'tipo documento no valido se aceptan [DNI,LE,LC,CI], reingrese!<br>';
                exit;
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
            if(isset($tipoCuenta) && !empty($tipoCuenta) && self::validarTipoCuenta($tipoCuenta)) {
                $this->_tipoCuenta = $tipoCuenta;
            }
            else{
                echo 'tipo de cuenta no valido se acepta [CC,CA],reingrese!<br>';
                exit;
            }
        }
        public function setMoneda($moneda){
            if(isset($moneda) && !empty($moneda) && self::validarMoneda($moneda)) {
                $this->_moneda = $moneda;
            }
            else{
                echo 'moneda no valida se aceptan [$,USS], reingrese!<br>';
                exit;
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
        private static function validarTipoCuenta($tipo){
            $tipos = ["CA","CC"];
            if(in_array(strtoupper($tipo),$tipos))
                return true;
            else
                return false;
        }

        private static function validarMoneda($moneda){
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
        private static function validarTipoDocumento($tipo){
            $tipos = ["DNI","LC","LE","CI"];
            if(in_array(strtoupper($tipo),$tipos))
                return true;
            else
                return false;
        }

        /**
         * Verifica si el saldo de la instancia
         * es mayor o menor al saldo que recibe.
         * @param float $saldo el saldo a comprobar.
         * @return bool true si es mayor, false sino.
         */
        public function verificarSaldo($saldo){
            return $this->getSaldo() > $saldo;
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
         * 
         * @return bool true si pudo actualizar o crear la cuenta,
         * false sino.
         */
        public static function cargarCuenta($cuenta,$saldo,$jsonFile,$imagen){
            $archivoGuardar = new Uploader('./ImagenesDeCuentas/2023/');
            $cuentas = Cuenta::leerJSON($jsonFile); 
            $cuentaBuscar  = $cuenta->buscarCuenta($cuentas,$cuenta->getNombre(),$cuenta->getApellido(),$cuenta->getTipoCuenta());
            
            if($cuentaBuscar !== null){//-->Existe
                $cuentaBuscar->setSaldo( $cuentaBuscar->getSaldo() + $saldo);
                // var_dump($cuenta->getSaldo());
                return self::actualizarCuenta($cuentas,$cuentaBuscar,$jsonFile);
            }
            else{//-->false, no existe 
                $cuentas[] = $cuenta;
                echo 'Creando cuenta...!<br>';
                $nombreImagen = $cuenta->getID().'_' . $cuenta->getTipoCuenta() . '.jpg';
                //-->Intento guardar la imagen del alta
                if(!$archivoGuardar->guardarImagen($imagen['tmp_name'],$nombreImagen)){
                    echo json_encode(['WARNING' => 'No se ha podido guardar la imagen de la cuenta!<br>']);    
                }
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
        public function buscarCuenta($cuentas,$nombre,$apellido,$tipoCuenta){ 
                foreach ($cuentas as $cuenta) {
                    // var_dump($cuenta);
                    if($cuenta->getNombre() === $nombre && $cuenta->getApellido() === $apellido &&
                     $cuenta->getTipoCuenta() === $tipoCuenta){
                        return $cuenta;
                } 
            }
            return null;
        }

        /**
         * Esta function me permite buscar una coincidencia
         * de una cuenta en un array.
         * 
         * @param array $cuentas el array de cuentas.
         * @param int $nroCuenta el nro de cuenta.
         * @param string $tipoCuenta el tipo de la cuenta
         * @param string||null $moneda puede no pasarse 
         * y compara por el nro de cuenta y el tipoCuenta.
         * En caso de pasarse $moneda se fijara la coincidencia
         * retornando la cuenta. Esto sirve para el punto 
         * ModificarCuenta, donde se busca por tipo y nro, 
         * y en el enunciado 3 se compara por las 3.
         * 
         * @return Cuenta||null
         */
        public static function buscarPorNumeroCuenta($cuentas, $nroCuenta, $tipoCuenta, $moneda = null) {
            foreach ($cuentas as $cuenta) {
                if ($cuenta->getID() === $nroCuenta && $cuenta->getTipoCuenta() === $tipoCuenta) {
                    if ($moneda === null || $cuenta->getMoneda() === $moneda) {
                        return $cuenta;
                    }
                }
            }
            return null;
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
                    return 'Si hay cuentas con tipo de cuenta: ' . $tipo . ' y numero: ' . $numeroCuenta . 
                    '<br> Su saldo es: $' . $cuenta->getSaldo() . ' y la moneda de ella es: ' . $cuenta->getMoneda();
                }
                if($cuenta->getTipoCuenta() === $tipo){
                    $cuentasConTipo[] = $cuenta;
                }
                if($cuenta->getID() === $numeroCuenta)
                { $cuentasConNro = $cuenta;}
            }

            if (!empty($cuentasConTipo) && empty($cuentasConNro)) {
                $msj = 'Si hay cuentas con tipo: ' . $tipo . ' pero el numero: ' . $numeroCuenta . ' no le pertenece';
            } elseif (!empty($cuentasConNro) && empty($cuentasConTipo)) {
                $msj = 'Solo hay cuentas con el numero: ' . $numeroCuenta . ' pero no con el tipo: ' . $tipo;
            } elseif (!empty($cuentasConTipo)) {
                $msj = 'No hay coincidencia de tipo de cuenta: ' . $tipo . ' y numero de cuenta: ' . $numeroCuenta;
            } else {
                $msj = 'No existe la combinacion de numero y tipo de cuenta.';
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
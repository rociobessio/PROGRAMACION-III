<?php
    require_once "./classes/Cuenta.php";
    require_once "./classes/Deposito.php";
    require_once "./classes/Retiro.php";

    class Ajuste{
//********************************************* ATRIBUTOS *********************************************
        public $_id;
        public $_motivoAjuste;
        public $_ajusteMonto;
        public $_numeroBuscado;
        // public $_numeroDeposito;
        public $_numeroCuenta;
        public $_ajusteSobre;//-->Extraccion o Deposito
//********************************************* GETTERS *********************************************
        public function getID(){
            return $this->_id;
        }
        public function getMotivoAjuste(){
            return $this->_motivoAjuste;
        }
        public function getAjusteMonto(){
            return $this->_ajusteMonto;
        }
        public function getNumeroBuscado(){
            return $this->_numeroBuscado;
        }
        public function getNumeroCuenta(){
            return $this->_numeroCuenta;
        }
        public function getAjusteSobre(){
            return $this->_ajusteSobre;
        }
//********************************************* SETTERS *********************************************
        public function setID($id){
            if (isset($id) && is_numeric($id)){
                $this->_id = $id;
            }
        }
        public function setMotivoAjuste($motivo){
            if(isset($motivo) && !empty($motivo)) {
                $this->_motivoAjuste = $motivo;
            }
        }
        public function setAjusteMonto($ajuste){
            if (isset($ajuste) && is_float($ajuste)){
                $this->_ajusteMonto = $ajuste;
            }
        }
        public function setNumeroBuscado($nroBuscado){
            if (isset($nroBuscado) && is_numeric($nroBuscado)){
                $this->_numeroBuscado = $nroBuscado;
            }
        }
        public function setNumeroCuenta($nroCuenta){
            if (isset($nroCuenta) && is_numeric($nroCuenta)){
                $this->_numeroCuenta = $nroCuenta;
            }
        }
        public function setAjusteSobre($sobre){
            if(isset($sobre) && !empty($sobre)) {
                $this->_ajusteSobre = $sobre;
            }
        }
//********************************************* CONSTRUCTOR *********************************************
        /**
         * Me permitira crear una instancia parametrizada de la clase
         * Ajuste.
         * @param int $id
         * @param string $motivo
         * @param float $ajusteMonto
         * @param int $nroBuscado
         * @param int $nroCuenta
         * @param string $ajusteSobre
         */
        public function __construct($id,$motivo,$ajusteMonto,$nroBuscado,$nroCuenta,$ajusteSobre)
        {
            $this->setID($id);
            $this->setMotivoAjuste($motivo);
            $this->setAjusteMonto($ajusteMonto);
            $this->setNumeroBuscado($nroBuscado);
            $this->setNumeroCuenta($nroCuenta);
            $this->setAjusteSobre($ajusteSobre);
        }
//********************************************* FUNCIONES *********************************************
        /**
         * Me permitira leer un archivo json de ajustes 
         * @param string $jsonFile el path del archivo
         * 
         * @return array retornara el array de ajustes
         * leidas del json.
         */
        public static function leerJSON($jsonFile){
            $ajustes = array();
            if(file_exists($jsonFile)){
                $archivo = fopen($jsonFile,"r");
                if($archivo){
                    $fileSize = filesize($jsonFile);
                    if($fileSize > 0){
                        $json = fread($archivo,$fileSize);
                        $ajustesJSON = json_decode($json,true); 
                        foreach($ajustesJSON as $ajuste){
                            array_push($ajustes,new Ajuste(
                                intval($ajuste["_id"]),
                                $ajuste["_motivoAjuste"],
                                floatval($ajuste["_ajusteMonto"]),
                                intval($ajuste["_numeroBuscado"]),
                                intval($ajuste["_numeroCuenta"]),
                                $ajuste["_ajusteSobre"],
                            ));
                        }
                    }
                    fclose($archivo);
                }
            }
            return $ajustes;
        }
        /**
         * Me permitira guardar un array de ajustes
         * en el archiov correspondiente.
         */
        public static function guardarJSON($ajustes,$jsonFile){
            $success = false;
            try {
                $file = fopen($jsonFile, "w");
                if ($file) {
                    $json = json_encode($ajustes, JSON_PRETTY_PRINT); 
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

        /**
         * Me permitira manejar si debo de aplicar el ajuste
         * sobre un retiro o sobre un deposito.
         * 
         * @param string $motivo el motivo del ajuste.
         * @param float $ajuste el monto del ajuste
         * @param string $sobre donde se hara el ajuste,
         * es decir, extracciones o depositos.
         * @param int $nroBuscado el numero que se busca
         * de deposito o retiro.
         * 
         * @return bool true si se pudo efectuar el ajuste
         * correctamente false sino.
         */
        public static function generarAjuste($motivo,$ajuste,$sobre,$nroBuscado){
            $cuentas = Cuenta::leerJSON('./archivos/banco.json'); 
            $ajustes = Ajuste::leerJSON('./archivos/ajustes.json');
            
            if($sobre === "extracciones"){
                $retiros = Retiro::leerJSON('./archivos/retiros.json');
                $retiroExistente = Retiro::buscarRetiro($retiros,$nroBuscado);
                if($retiroExistente !== null){
                    return self::aplicarAjusteExtraccion($motivo,$ajuste,$retiroExistente,$retiros,$cuentas,$ajustes);
                }
                else{
                    echo 'No existe un numero de extraccion/retiro bajo el numero: ' . $nroBuscado . '<br>';
                    return false;
                }
            }
            else if($sobre === "depositos"){
                $depositos = Deposito::leerJSON('./archivos/depositos.json');
                $depositoExistente = Deposito::buscarDeposito($depositos, $nroBuscado);
                if($depositoExistente !== null){
                    return self::aplicarAjusteDeposito($motivo,$ajuste,$depositoExistente,$depositos,$cuentas,$ajustes);
                }
                else{
                    echo 'No existe un numero de deposito bajo el numero: ' . $nroBuscado . '<br>';
                    return false;
                }
            }
        
            return false;
        }

        /**
         * Se podra aplicar un ajuste sobre un retiro existente.
         * verifica que el importe del retiro sea mayor al ajuste
         * ingresado, que exista la cuenta, y que se pueda actualizar
         * tanto el retiro como la cuenta existentes.
         * 
         * @param string $motivo motivo del ajuste.
         * @param float $ajuste el monto del ajuste.
         * @param Retiro $retiro el retiro a modificar.
         * @param array $retiros el para el array de los 
         * retiros.
         * @param array $cuentas se pasa el array de cuentas.
         * @param array $ajustes el array de ajustes.
         * 
         * @return bool true si pudo aplicar el ajuste
         * correctamente, false sino.
         */
        private static function aplicarAjusteExtraccion($motivo, $ajuste, $retiro, &$retiros, &$cuentas, &$ajustes) { 
            if ($retiro->verificarImporte($ajuste)) {//-->verfico que el ajuste no sea mayor al importe de la extraccion
                $retiro->setImporteRetiro($retiro->getImporteRetiro() - $ajuste);

                $cuentaExistente = Cuenta::buscarPorNumeroCuenta($cuentas, $retiro->getNumeroCuenta(), $retiro->getTipoCuenta());
                if ($cuentaExistente !== null) {

                    $cuentaExistente->setSaldo($cuentaExistente->getSaldo() + $ajuste);
                    //-->Actualizo el retiro y la cuenta
                    if ($retiro->actualizarRetiro($retiros) && Cuenta::actualizarCuenta($cuentas, $cuentaExistente, './archivos/banco.json')) {
                        $ajustes[] = new Ajuste(empty($ajustes) ? 1 : (count($ajustes) + 1), $motivo, $ajuste, $retiro->getID(), $retiro->getNumeroCuenta(), 'extracciones');
                        return self::guardarJSON($ajustes, './archivos/ajustes.json');
                    }
                } else {
                    echo 'No existe la cuenta en el banco!<br>';
                }
            } else {
                echo 'El ajuste solicitado es mayor al importe existente de la extracción!<br>';
            }
            return false;
        }

        /**
         * Se podra aplicar un ajuste sobre un deposito existente.
         * verifica que el importe del deposito sea mayor al ajuste
         * ingresado, que exista la cuenta, y que se pueda actualizar
         * tanto el deposito como la cuenta existentes.
         * 
         * @param string $motivo motivo del ajuste.
         * @param float $ajuste el monto del ajuste.
         * @param Deposito $deposito el deposito a modificar.
         * @param array $depositos se para el array de los 
         * depositos.
         * @param array $cuentas se pasa el array de cuentas.
         * @param array $ajustes el array de ajustes.
         * 
         * @return bool true si pudo aplicar el ajuste
         * correctamente, false sino.
         */
        private static function aplicarAjusteDeposito($motivo, $ajuste, $deposito, &$depositos, &$cuentas, &$ajustes) { 
            if ($deposito->verificarImporte($ajuste)) {//-->Se suma el ajuste al deposito
                $deposito->setImporte($deposito->getImporte() + $ajuste);
                $cuentaExistente = Cuenta::buscarPorNumeroCuenta($cuentas, $deposito->getNumeroCuenta(), $deposito->getTipoCuenta());

                if ($cuentaExistente !== null) {
                    $cuentaExistente->setSaldo($cuentaExistente->getSaldo() + $ajuste);

                    //-->Veo de actualizar el deposito y la cuenta
                    if ($deposito->actualizarDeposito($depositos) && Cuenta::actualizarCuenta($cuentas, $cuentaExistente, './archivos/banco.json')) {
                        $ajustes[] = new Ajuste(empty($ajustes) ? 1 : (count($ajustes) + 1), $motivo, $ajuste, $deposito->getID(), $deposito->getNumeroCuenta(), 'depositos');
                        return self::guardarJSON($ajustes, './archivos/ajustes.json');
                    }
                } else {
                    echo 'No existe la cuenta en el banco!<br>';
                }
            } else {
                echo 'El ajuste solicitado es mayor al importe existente del depósito!<br>';
            }
            return false;
        }
    }
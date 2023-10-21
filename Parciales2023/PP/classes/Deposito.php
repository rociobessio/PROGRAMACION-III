<?php

    require_once "./classes/Cuenta.php";
    require_once "./classes/Uploader.php";

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
         * @param Cuenta||null $cuenta la cuenta sobre la cual
         * se hara el deposito.
         * @param array $cuentas el array de cuentas.
         * @param float $importe el importe del deposito.
         * @param string $moneda la moneda
         * @param $imagen la imagen que se guardara con el  
         * deposito.
         * 
         * @return bool true si pudo generarlo, false sino.
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
                    
                    $nombreImagen = $cuenta->getTipoCuenta(). '_' . $cuenta->getID() .'_' . $nuevoDeposito->getID() . '.jpg';
                    if(!$archivoGuardar->guardarImagen($imagen['tmp_name'],$nombreImagen)){//-->Intento guardar la imagen
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
         * Me permite calcular el total depositado por
         * tipo de cuenta y moneda en un dia en particular.
         * 
         * @param string $moneda la moneda buscada.
         * @param string $tipoCuenta el tipo de la cuenta.
         * @param string $fecha la fecha en particular a 
         * buscar, puede ser null y  se buscaran las del
         * dia de ayer.
         * 
         * @param bool 
         */
        public static function calcularTotalDepositos($moneda,$tipoCuenta,$fecha = null){
            $retorno = false;
            $depositos = self::leerJSON('./archivos/depositos.json');
            $totalDepositado = 0; 
        
            if (!empty($depositos) && $depositos !== null) {
                if ($fecha === null) {
                    $fecha = date('Y-m-d', strtotime('yesterday'));//-->Sino recibo es la fecha de ayer
                }
                foreach($depositos as $deposito){
                    if($deposito->getFechaDeposito() == $fecha &&
                        $deposito->getMoneda() == $moneda &&
                        $deposito->getTipoCuenta() == $tipoCuenta){  
                            $totalDepositado += $deposito->getImporte();
                            $retorno = true;
                        }
                }
                if($retorno){
                    echo 'El monto total depositado con tipo de cuenta: ' . $tipoCuenta. ' en la fecha: ' . $fecha . '<br>' . 
                    'es: ' . $moneda  . $totalDepositado .'<br>';
                }
            }
            return $retorno;
        }   
               /**
         * Me permitira buscar los movimientos (depositos)
         * realizados por un usuario.
         * @param array $cuentas el array de cuentas
         * @param string $email el email a verificar
         * @return string el mensaje final.
         */
        public static function buscarYListarMovimientosUsuario($cuentas,$email){
            $depositos = self::leerJSON('./archivos/depositos.json');
            $retorno = false; 
            foreach($cuentas as $cuenta){
                if($cuenta->getEmail() === $email){//-->Si coinciden los mails
                    foreach($depositos as $deposito){
                        if($deposito->getNumeroCuenta() === $cuenta->getID()){ 
                            $deposito->listarDeposito();
                            $retorno = true;
                        }
                    }
                }
            }
            return $retorno; 
        }

        /**
         * Me permite buscar y listar los depositos entre dos fechas ingresadas, 
         * ordenandolas por nombre de cuenta.
         * 
         * @param string $fechaInicio
         * @param string $fechaFin
         * @param array $depositos
         * @param array $cuentas
         * 
         * 1. Con array_filter filtro el array de $depositos, $deposito es la variable. Le paso variables externas,
         * para que pueda usarlas ($fechaIncia,$fechaFin y $cuentas). Busco si hay una cuenta asociada.
         * Si la cuenta no es null y las fechas del deposito cumplen con las fechas ingresadas por parametro entonces
         * el array_filter guardara el elemento en $depositos filtrados.
         * 
         * 2. Ordeno el array filtrado por nombre mediante una function anonima. Obtien el nombre
         * de la cuenta mediante buscando la cuenta por su numero y tipo de cuenta. 
         * ...)->getNombre() devuelve el nombre de la cuenta. Luego compara con la funcion strcmp
         * $nombreA y $nombreB
         * 
         * 3. Por último listara los depositos que se filtraron
         * 
         * @return bool true si pudo buscar y listar correctamente,
         * false caso contrario.
         */
        public static function buscarYListarDepositosEntreFechas($fechaInicio, $fechaFin, $depositos, $cuentas) {
            if (empty($depositos) || $depositos === null) {
                return false;
            }
            
            //-->#1
            $depositosFiltrados = array_filter($depositos, function ($deposito) use ($fechaInicio, $fechaFin, $cuentas) {
                $cuentaAsociada = Cuenta::buscarPorNumeroCuenta($cuentas, $deposito->getNumeroCuenta(), $deposito->getTipoCuenta(), $deposito->getMoneda());
                return (
                    $cuentaAsociada !== null &&
                    $fechaInicio <= $deposito->getFechaDeposito() &&
                    $fechaFin >= $deposito->getFechaDeposito()
                );
            });
            
            //-->#2
            usort($depositosFiltrados, function ($a, $b) use ($cuentas) {
                $nombreA = Cuenta::buscarPorNumeroCuenta($cuentas, $a->getNumeroCuenta(), $a->getTipoCuenta())->getNombre();
                $nombreB = Cuenta::buscarPorNumeroCuenta($cuentas, $b->getNumeroCuenta(), $b->getTipoCuenta())->getNombre();
                
                return strcmp($nombreA, $nombreB);
            });
            
            //-->#3
            if (!empty($depositosFiltrados)) {
                foreach ($depositosFiltrados as $deposito) {
                    $deposito->listarDeposito();
                }
                return true;
            }
        
            return false;
        } 

        /**
         * Busca y lista los depositos por el tipo de 
         * cuenta ingresados.
         * @param string $tipo el tipo de cuenta.
         * 
         * @return bool true si es que hay al menos
         * un deposito filtrado, false sino.
         */
        public static function buscarYListarDepositosTipoCuenta($tipo){
            $depositos = self::leerJSON('./archivos/depositos.json'); 
            $retorno = false;

            foreach ($depositos as $deposito) {
                if ($deposito->getTipoCuenta() === $tipo) { 
                    $retorno = true;
                    $deposito->listarDeposito();
                }
            }
            return $retorno; 
        }

        /**
         * Busca y filtra depositos por el tipo de moneda
         * ingresado.
         * 
         * @param string $tipoMoneda el tipo de moneda.
         * @return bool true si es que existe al menos
         * un deposito filtrado, false.
         */
        public static function buscarYListarDepositosTipoMoneda($tipoMoneda){
            $depositos = self::leerJSON('./archivos/depositos.json'); 
            $retorno = false;
            foreach ($depositos as $deposito) {
                if ($deposito->getMoneda() === $tipoMoneda) { 
                    $retorno = true;
                    $deposito->listarDeposito();
                }
            }
            return $retorno;
        }

        /**
         * Me permitira listar la informacion de 
         * un la instancia de un deposito
         */
        private function listarDeposito(){  
            echo "<ul>"; 
            echo "<li>";
            echo "Numero Cuenta: " . $this->getNumeroCuenta() . "<br>";
            echo "Tipo Cuenta: " . $this->getTipoCuenta() . "<br>";
            echo "Moneda: " . $this->getMoneda() . "<br>";
            echo "Importe: $" . $this->getImporte() . "<br>";
            echo "Fecha Deposito: " . $this->getFechaDeposito() . "<br>"; 
            echo "</li>"; 
            echo "</ul>"; 
        }

        /**
         * Me permitira buscar si hay coincidencia 
         * con el id del numero de deposito.
         */
        public static function buscarDeposito($depositos,$nroDeposito){
            foreach ($depositos as $deposito) {
                if($deposito->getID() === $nroDeposito){
                    return $deposito;
                }
            }
            return null;
        }
                
        /**
         * Me permite actualizar un instancia de 
         * la clase Deposito mediante coincidencia
         * de ids. Guardandola en el archivo json.
         * 
         * @param array $depositos
         * @return bool true si pudo guardar
         * false sino.
         */
        public function actualizarDeposito(&$depositos){
            foreach ($depositos as &$deposito) { 
                if ($deposito->getID() == $this->getID()) {
                    $deposito = $this; 
                    break;
                }
            }
            return self::guardarJSON($depositos,'./archivos/depositos.json');
        } 

        /**
         * Me permite verificar si el importe de
         * la instancia es mayor al valor que recibo.
         * 
         * @param float $valor el valor a validar
         * @return bool true si se cumple, false sino.
         */
        public function verificarImporte($valor){
            return $this->getImporte() >= $valor;
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
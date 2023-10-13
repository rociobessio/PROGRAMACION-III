    <?php

        class Cupon{
    //********************************************* ATRIBUTOS *********************************************
            public $_id;
            public $_numeroPedido;
            public $_usuario;
            public $_estado;
            public $_descuento;
            public $_montoFinal;
    //********************************************* GETTERS *********************************************
            public function getID(){
                return $this->_id;
            }
            public function getUsuario(){
                return $this->_usuario;
            }
            public function getEstado(){
                return $this->_estado;
            }
            public function getDescuento(){
                return $this->_descuento;
            }
            public function getMontoFinal(){
                return $this->_montoFinal;
            }
            public function getNumeroPedido(){
                return $this->_numeroPedido;
            }
    //********************************************* SETTERS *********************************************
            public function setID($value){
                if (isset($value) && is_numeric($value)){
                    $this->_id = $value;
                }
            }
            public function setUsuario($usuario){
                if(isset($usuario) && !empty($usuario)) {
                    $this->_usuario = $usuario;
                }
            }
            public function setEstado($estado){
                $this->_estado = $estado;
            }
            public function setDescuento($descuento){
                if (isset($descuento) && is_numeric($descuento)){
                    $this->_descuento = $descuento;
                }
            }
            public function setMontoFinal($montoFinal){
                if (isset($montoFinal) && is_float($montoFinal)){
                    $this->_montoFinal = $montoFinal;
                }
            }
            public function setNumeroPedido($numeroPedido){
                if (isset($numeroPedido) && is_numeric($numeroPedido)){
                    $this->_numeroPedido = $numeroPedido;
                }
            }
    //********************************************* CONSTRUCTOR *********************************************
            public function __construct($id,$numeroPedido,$usuario,$estado,$descuento,$montoFinal)
            {
                $this->setID($id);
                $this->setUsuario($usuario);
                $this->setEstado($estado);
                $this->setDescuento($descuento);
                $this->setMontoFinal($montoFinal);
                $this->setNumeroPedido($numeroPedido);
            }
    //********************************************* FUNCIONES *********************************************
            
            /**
             * Se fija si el obkjeto que recibe por parametro
             * coincide con el id de la instancia.
             * 
             * @param object $object el objeto a
             * analizar
             */
            private function __Equals($object){
                return $this->getID() == $object->getID();
            }

            /**
             * Verifica si el cupon se encuentra dentro del array
             * llamando al metodo __Equals que verifica por
             * coindicendia de id.
             * 
             * @param array $cupones el array de cupones.
             * @return bool||null
             */
            public function buscarCuponEnArray($cupones){
                if(isset($cupones) && is_array($cupones)){
                    foreach($cupones as $objecto){
                        if($this->__Equals($objecto)){
                            return true;
                        }
                    }
                }
                return false;
            }

            /**
             * A difrenecia de buscarCuponEnArray
             * este devolvera un cupon si existe 
             * o null sino. Se busca que coincida el mail
             * con algunoo en el array y si su estado de
             * false (sin usar).
             * 
             * @param string $email del usuario.
             * @param array $cupones el array de cupones.
             * 
             * @return object||null retorna el objeto si 
             * cumple la coindicion, null sino.
             */
            public static function obtenerCupon($email, $cupones){
                foreach ($cupones as $cupon) {
                    if ($cupon->getUsuario() == $email && !$cupon->getEstado()) {
                        return $cupon;
                    }
                }
                return null;
            }
            


            /**
             * Esta funcion me permitira actualizar el
             * estado y monto de un cupon.
             * 
             * @param string $emailUsuario el email del usuario.
             * @param object $producto el objeto sobre le cual
             * se haran los calculos para el monto del descuento.
             * @param int $cantidad cantidad solicitada.
             * 
             * @return bool true si pudo realizarse la actualizacion
             * false sino.
             */
            public static function actualizarCupon($emailUsuario, $producto, $cantidad)
            {
                $archivoJSONCupones = './archivos/cupones.json';
                $cupones = Cupon::leerJSON($archivoJSONCupones); 
            
                foreach ($cupones as $cupon) {
                    if ($cupon->getUsuario() == $emailUsuario && !$cupon->getEstado()) {
                        $descuento = $cupon->CalcularDescuento($producto->getPrecio() * $cantidad);
            
                        if ($descuento > 0) {
                            //-->Guardo el descuento en el cupon
                            $cupon->setMontoFinal($descuento);
            
                            //-->Actualizo el estado a USADO (true)
                            $cupon->setEstado(true);
            
                            //-->Termino guardando el cupon en el archiov
                            return self::guardarJSON($cupones,$archivoJSONCupones);
                        }
                    }
                }
                return false;
            }  

            /**
             * Me permite calcular el descuento de un precio.
             * @param float||int $precioPrdocuto el precio 
             * del producto.
             */
            public function CalcularDescuento($precioProducto){
                if($precioProducto >= 0 && $this->getDescuento() >=1){
                    $descuento = ($precioProducto * $this->getDescuento()) / 100 ;
                    return $descuento;
                }
                else
                    return 0;
            }

            /**
             * Esta funcion me permite generar un cupon o modificarlo.
             * 
             * @param object el cupon a buscar o crear.
             * @param string recibe la accion a realizar, 'crear'
             * generara un nuevo cupon. 'actualizar' modifica
             * el estado y el monto final del cupon.
             * 
             * @return bool true si pudo generar/actualizar,
             * false sino pudo.
             */
            public static function generarCupon($cupon,$accion){
                $jsonFileCupones = './archivos/cupones.json';
                $cupones = Cupon::leerJSON($jsonFileCupones);
                if(!$cupon->buscarCuponEnArray($cupones)){//-->Quiere decir que NO existe
                    if($accion == 'crear'){
                        var_dump($cupon);
                        echo '[Creando cupon...!]<br>';
                        array_push($cupones, $cupon);
                        var_dump($cupones);
                        return self::guardarJSON($cupones,$jsonFileCupones);
                    }
                }
                return false;
            }


            /**
             * Me permitira retornar una lista
             * de cupones con estado.
             * 
             * @param array $cupones el array de cupones.
             * @return array el array foñtradp de cupones.  
             */
            public static function obtenerCuponesConEstado($cupones) {
                $cuponesConEstado = array();
                foreach ($cupones as $cupon) {
                    $estado = !$cupon->getEstado() ? 'No Usado' : 'Usado';
                    $cuponesConEstado[] = array(
                        'ID' => $cupon->getID(),
                        'estado' => $estado,
                    );
                }
                return $cuponesConEstado;
            }

            /**
             * Imprimira una lista de cupones
             * y su estado (usado o no).
             * 
             * @param array $cuponesConEstado
             * el array a imprimir.
             */
            public static function listarCuponesEstado($cuponesConEstado){
                foreach ($cuponesConEstado as $cuponInfo) {
                    echo 'Cupón ID: ' . $cuponInfo['ID'] . '<br>';
                    echo 'Estado: ' . ($cuponInfo['estado']) . '<br>';
                    echo '<hr>';
                }
            }
            

            /**
             * Esta funcion lee un archivo json de pizzas.
             * 
             * @param string la ubicacion del archivo .json
             * @return array retornara un array vacio sino pudo cargar,
             * o retorna el array cargado.
             */
            public static function leerJSON($jsonFile){
                $cupones = array();
                if(file_exists($jsonFile)){
                    $archivo = fopen($jsonFile, "r");
                    if ($archivo) {
                        $fileSize = filesize($jsonFile);
                        if ($fileSize > 0) {
                            $json = fread($archivo, $fileSize);
                            $cuponesJson = json_decode($json, true);
                            foreach ($cuponesJson as $cupon) {
                                array_push($cupones, new Cupon(
                                    intval($cupon["_id"]), 
                                    intval($cupon["_numeroPedido"]), 
                                    $cupon["_usuario"],
                                    $cupon["_estado"], 
                                    intval($cupon["_descuento"]), 
                                    floatval($cupon["_montoFinal"]),  
                                ));
                            }
                        }
                        fclose($archivo);
                    }
                }
                return $cupones;
            }

            /**
             * Me permitira guardar el array de productos en el archivo
             * json.
             * @param array el array de productos.
             * @param string ruta del archivo
             * @return bool true si pudo false sino.
             */
            public static function guardarJSON($cupones, $jsonFilename){
                $success = false;
                try {
                    $file = fopen($jsonFilename, "w");
                    if ($file) {
                        $json = json_encode($cupones, JSON_PRETTY_PRINT);
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
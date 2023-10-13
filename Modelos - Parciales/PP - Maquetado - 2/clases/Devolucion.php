<?php

    include_once "./clases/Cupon.php";

    class Devolucion{
//********************************************* ATRIBUTOS *********************************************
        public $_id;
        public $_numeroPedido;
        public $_causaDevolucion;      
        public $_idCupon;  
//********************************************* GETTERS *********************************************
        public function getId(){
            return $this->_id;
        }    
        public function getNumeroPedido(){
            return $this->_numeroPedido;
        }
        public function getCausaDevolucion(){
            return $this->_causaDevolucion;
        }
        public function getIDCupon(){
            return $this->_idCupon;
        }
//********************************************* SETTERS *********************************************
        public function setID($id){
            if (isset($id) && is_numeric($id)){
                $this->_id = $id;
            }
        }
        public function setNumeroPedido($numero){
            if (isset($numero) && is_numeric($numero)){
                $this->_numeroPedido = $numero;
            }
        }
        public function setCausaDevolucion($causa){
            if(isset($causa) && !empty($causa)) {
                $this->_causaDevolucion = $causa;
            }
        }
        public function setIDCupon($idCupon){
            if (isset($idCupon) && is_numeric($idCupon)){
                $this->_idCupon = $idCupon;
            }
        }
//********************************************* CONSTRUCTOR *********************************************
        public function __construct($id,$numeroPedido,$causaDevolucion,$idCupon){
            $this->setID($id);
            $this->setNumeroPedido($numeroPedido);
            $this->setCausaDevolucion($causaDevolucion);
            $this->setIDCupon($idCupon);
        }
//********************************************* FUNCIONES *********************************************

        /**
         * Me genera una nueva devolucion y la guarda en el archiv
         * json que recibe.
         */
        public static function generarDevolucion($devolucion,$jsonFileDevoluciones){
            $devoluciones = Devolucion::leerJSON($jsonFileDevoluciones);
            array_push($devoluciones,$devolucion);
            return self::guardarJSON($devoluciones,$jsonFileDevoluciones);
        }

        public static function obtenerDevolucionesConCupones($cupones,$devoluciones){
            $devolucionesConCuponesYEstados = array();
            foreach($devoluciones as $devolucion){
                $idCupon = $devolucion->getIDCupon();//-->Obtengo la id de cupon.
                $cupon = null;

                foreach($cupones as $c){
                    if($c->getID() == $idCupon){//-->Busco coincidencia 
                        $cupon = $c;
                        break;
                    }    
                }

                if ($cupon) {
                    $estado = $cupon->getEstado();
                    $devolucionesConCuponesYEstados[] = [
                        'devolucion' => $devolucion,
                        'cupon' => $cupon,
                        'estado' => $estado,
                    ];
                }
            }
            return $devolucionesConCuponesYEstados;
        }

        /**
         * Me permitira listar devoluciones con cupones
         * en formato <ul> <li></li> <ul>
         * devolviendo la cadena.
         * 
         * @param array $devolucionesConCupones el array a 
         * imprimir.
         * @return string la string a imprimir.
         */
        public static function listarDevolucionesConCuponesYEstado($devolucionesConCuponesYEstados) {
            foreach ($devolucionesConCuponesYEstados as $item) {
                echo 'Devolución ID: ' . $item['devolucion']->getId() . '<br>';
                echo 'Cupón ID: ' . $item['cupon']->getID() . '<br>';
                
                echo 'Estado del Cupón: ' . ($item['estado'] === true ? 'Usado' : 'No Usado') . '<br>';
                echo '<hr>';
            }
        }

        /**
         * Me permitira listar una devolucion con cupon
         * y toda su informacion.
         * @param array el array a imprimir.
         */
        public static function listarDevolucionesConCupones($devolucionesConCupones){
            foreach ($devolucionesConCupones as $item) {
                echo 'Devolución ID: ' . $item['devolucion']->getId() . '<br>';
                echo 'Devolucion Causa: ' . $item['devolucion']->getCausaDevolucion() . '<br>';
                echo 'Numero de Pedido: ' . $item['devolucion']->getNumeroPedido() . '<br>';
                echo 'Cupón ID: ' . $item['cupon']->getID() . '<br>';
                echo 'Cupon Usuario: ' . $item['cupon']->getUsuario() . '<br>';
                echo 'Cupon Descuento: ' . $item['cupon']->getDescuento() . '<br>';
                echo 'Estado del Cupón: ' . ($item['estado'] === true ? 'Usado' : 'No Usado') . '<br>';
                echo '<hr>';
            }
        }

        /**
         * Esta funcion lee un archivo json de devoluciones.
         * 
         * @param string la ubicacion del archivo .json
         * @return array retornara un array vacio sino pudo cargar,
         * o retorna el array cargado.
         */
        public static function leerJSON($jsonFile){
            $devoluciones = array();
            if(file_exists($jsonFile)){
                $archivo = fopen($jsonFile, "r");
                if ($archivo) {
                    $fileSize = filesize($jsonFile);
                    if ($fileSize > 0) {
                        $json = fread($archivo, $fileSize);
                        $devolucionesJson = json_decode($json, true);
                        foreach ($devolucionesJson as $devolucion) {
                            array_push($devoluciones, new Devolucion(
                                intval($devolucion["_id"]), 
                                intval($devolucion["_numeroPedido"]),
                                $devolucion["_causaDevolucion"],  
                                $devolucion["_idCupon"],
                            ));
                        }
                    }
                    fclose($archivo);
                }
            }
            return $devoluciones;
        }

        /**
         * Me permitira guardar el array de productos en el archivo
         * json.
         * @param array el array de productos.
         * @param string ruta del archivo
         * @return bool true si pudo false sino.
         */
        public static function guardarJSON($devoluciones, $jsonFilename){
            $success = false;
            try {
                $file = fopen($jsonFilename, "w");
                if ($file) {
                    $json = json_encode($devoluciones, JSON_PRETTY_PRINT);
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
<?php

    include_once "Producto.php";

    class Venta{
//********************************************* ATRIBUTOS *********************************************
        public $_idVenta;
        public $_emailUsuario;
        public $_fechaVenta;
        public $_sabor;
        public $_tipo;
        public $_totalVenta;
        public $_cantidad;
        public $_numeroPedido;
        // public $_imagenVenta;
//********************************************* SETTERS *********************************************
        public function setID($id){
            if (isset($id) && is_numeric($id)){
                $this->_idVenta = $id;
            }
        }
        public function setNumeroPedido($numero){
            if (isset($numero) && is_numeric($numero)){
                $this->_numeroPedido = $numero;
            }
        }
        public function setEmailUsuario($email){
            if(isset($email) && !empty($email)) {
                $this->_emailUsuario = $email;
            }
        }
        public function setFechaVenta($fecha){
            if (!empty($fecha)) {
                $this->_fechaVenta = $fecha;
            }
        }
        public function setSabor($sabor){
            if (!empty($sabor)) {
                $this->_sabor = $sabor;
            }
        }
        public function setTipo($tipo){
            if(!empty($tipo)) {
                $this-> _tipo = $tipo;}
        }
        public function setTotalVenta($total){
            if (isset($total) && is_float($total)){
                $this->_totalVenta = $total;
            }
        }
        public function setCantidad($cantidad){
            if (isset($cantidad) && is_numeric($cantidad)){
                $this->_cantidad = $cantidad;
            }
        }
        // public function setImagen($imagen){
        //     if(!empty($imagen)) {
        //         $this->_imagenVenta= $imagen;}
        // }
//********************************************* GETTERS *********************************************
        public function getID(){
            return $this->_idVenta;
        }
        public function getEmailUsuario(){
            return $this->_emailUsuario;
        }
        public function getFechaVenta(){
            return $this->_fechaVenta;
        }
        public function getSabor(){
            return $this->_sabor;
        }
        public function getTipo(){
            return $this->_tipo;
        }
        public function getTotalVenta(){
            return $this->_totalVenta;
        }
        public function getCantidad(){
            return $this->_cantidad;
        }
        // public function getImagen(){
        //     return $this->_imagenVenta;
        // }
        public function getNumeroPedido(){
            return $this->_numeroPedido;
        }
//********************************************* CONSTRUCTOR *********************************************
        public function __construct($id,$emailUsuario,$fechVenta,$sabor,$tipo,$totalVenta,$cantidad,$numeroPedido)
        {
            $this->setID($id);
            $this->setEmailUsuario($emailUsuario);
            $this->setFechaVenta($fechVenta);
            $this->setSabor($sabor);
            $this->setTipo($tipo);
            $this->setTotalVenta($totalVenta);
            $this->setCantidad($cantidad);
            $this->setNumeroPedido($numeroPedido);
            // $this->setImagen(($imagen !== null) ? $imagen : '');
        }
//********************************************* FUNCIONES *********************************************
    
        /**
         * Me permite generar una venta realizando todas las
         * verificaciones necesarias, ver si existe el producto,
         * si hay stock y se puede actualizar el producto y
         * por ultimo si realiza la venta.
         * 
         * @param object el producto a vender
         * @param array el array de productos
         * @param int la cantidad a vender
         * @param string el email del usuario.
         * @param array eñ array de cupones
         * 
         * @return bool true si finalmente pudo realizarse 
         * la venta, false caso contrario.
         */
        public static function generarVenta($producto, $productos, $cantidad, $emailUsuario, $cupones) {
            $jsonFileProducto = './archivos/productos.json';
            $jsonFileVentas = './archivos/ventas.json';
            $ventas = Venta::leerJSON($jsonFileVentas);

            if ($producto !== null) {
                if ($producto->verificarStock($cantidad)) {
                    $cupon = Cupon::ObtenerCupon($emailUsuario, $cupones);

                    //-->Verifico si existe el cupon
                    $conDescuento = ($cupon !== null) ? true : false;
                    $totalVenta = $producto->getPrecio() * $cantidad;

                    //-->Actualizo el producto y genero la venta
                    if (Producto::actualizarProducto($productos, $producto, $jsonFileProducto)) {
                        $nuevaVenta = new Venta(
                            empty($ventas) ? 1 : (count($ventas) + 1), $emailUsuario,
                            (new DateTime('now'))->format('Y-m-d'),
                            $producto->getSabor(),
                            $producto->getTipo(),
                            $conDescuento ? $cupon->CalcularDescuento($totalVenta) : $totalVenta,
                            $cantidad,
                            mt_rand(1, 10000)
                        );
                        // var_dump($nuevaVenta);

                        $ventas[] = $nuevaVenta;

                        if($cupon !== null){//-->Actualizo el cupon:
                            if(Cupon::actualizarCupon($emailUsuario, $producto, $cantidad)){
                                echo 'Cupon usado y actualizado!<br>';
                                return self::guardarJSON($ventas, $jsonFileVentas);    
                            }
                            else
                                echo 'Ocurrio un error al querer actualizar el cupon!'; 
                        }
                        else//-->Venta sin descuento
                            return self::guardarJSON($ventas, $jsonFileVentas);
                    } else {
                        return false;
                    }
                } else {
                    echo 'No hay stock suficiente para realizar la venta!<br>';
                    return false;
                }
            } else {
                return false;
            }
        }

        /**
         * Esta funcion estatica me permitira modificar una venta  en
         * el archiov JSON.
         * 
         * @param int el numero del pedido solicitado
         * @param string el sabor del producto
         * @param string el tipo del producto
         * @param int cantidad a modificar del producto
         * @param string el email del usuario a modificar
         * 
         * @return bool true si pudo realizarse la moficiacion,
         * false sino.
         */
        public static function modificarVenta($numeroPedido,$sabor,$tipo,$cantidad,$emailUsuario){
            $jsonFileVentas = './archivos/ventas.json';
            $ventas = Venta::leerJSON($jsonFileVentas);
            $venta = Venta::buscarVenta($ventas,$numeroPedido);

            if($venta !== null){
                $venta->setSabor($sabor);
                $venta->setCantidad($cantidad);
                $venta->setTipo($tipo);
                $venta->setEmailUsuario($emailUsuario);
                $key = array_search($venta,$ventas);//-->Busco su key para luego actualizarla

                $ventas[$key] = $venta;//-->Modifico en el array esa venta.

                return self::guardarJSON($ventas,$jsonFileVentas);
            }
            
            return false;
        }

        /**
         * Me permitirá eliminar una venta del archivo mediante
         * su numero de pedido. Primero verificara que exista la
         * venta y que se pueda mover la imagen de la venta
         * a otro directorio.
         * 
         * @param int el numero del pedido
         * 
         * @return bool true si pudo eliminar correctametne
         * false sino.
         */
        public static function eliminarVenta($numeroPedido){
            $pathVentas = './archivos/ventas.json';
            $ventas = Venta::LeerJSON($pathVentas); 
            $venta = Venta::BuscarVenta($ventas,$numeroPedido); 
            // var_dump($venta);

            if($venta !== null){//-->Existe
                $nombreImagen = Uploader::crearPathImagenVenta($venta->getEmailUsuario(), $venta->getSabor(), $venta->getTipo());
                $directorioBackup = './BACKUPVENTAS/'; 
                $imagenActual = './ImagenesDeLaVenta/' . $nombreImagen;//-->Path actual de la imagen
                // var_dump($imagenActual);
                $uploader = new Uploader($directorioBackup);
                if ($uploader->moverImagenABackUp($imagenActual, $directorioBackup, $nombreImagen)) {
                    //-->Si pudo mover la imagen elimino del array la venta
                    $key = array_search($venta, $ventas);
                    unset($ventas[$key]);

                    return self::guardarJSON($ventas,$pathVentas);
                }
            }
            return false;
        }

        /**
         * Me permite buscar si existe o no una venta
         * mediante su nuemro de pedido.
         * @param array el array de ventas
         * @param int el numero de pedido
         * @return object||null
         */
        public static function buscarVenta($ventas,$numeroPedido){
            foreach ($ventas as &$venta) {
                if ($venta->getNumeroPedido() === $numeroPedido) {
                    return $venta;
                }
            }
            return null;
        }

        /**
         * Me permitira calcular la cantidad de pizzas vendidas en un 
         * dia particular, de no pasarse la fecha se mostraran las de 
         * la actualidad.
         * 
         * @return int cantidad de pizzas vendidas.
         */
        public static function calcularTotalPizzasVendidas($fecha = null) {
            $json_file = './archivos/ventas.json';
            $ventas = Venta::LeerJSON($json_file);
            $pizzasVendidas = 0;
        
            if (!empty($ventas) && $ventas !== null) {
                if ($fecha !== null) {
                    // Filtrar ventas por la fecha especificada
                    $ventas = array_filter($ventas, function ($venta) use ($fecha) {
                        return substr($venta->getFechaVenta(), 0, 10) === $fecha;
                    });
                }
        
                foreach ($ventas as $venta) {
                    $pizzasVendidas += $venta->getCantidad();
                }
            }
            return $pizzasVendidas;
        }
        

        /**
         * Me permite filtrar el array de ventas y buscar por medio
         * de su email.
         * @param string el email del usuario a filtrar.
         */
        public static function buscarVentaPorUsuario($emailUsuario,$ventas){
            if (!empty($ventas) && $ventas !== null) {
                $ventasUsuario = array();
        
                foreach ($ventas as $venta) {
                    if ($venta->getEmailUsuario() === $emailUsuario) {
                        $ventasUsuario[] = $venta;
                    }
                }
        
                Venta::ListarVentas($ventasUsuario);
            } else {
                echo "[Ocurrió un error al intentar abrir el archivo de ventas!]";
            }
        }

        /**
         * Me permite filtrar las ventas mediante 
         * el sabor requerido, imprimiendo una lista
         * como resultado.
         * 
         * @param string el sabor del producto a filtrar.
         */
        public static function buscarVentaPorSabor($sabor,$ventas){
            if (!empty($ventas) && $ventas !== null) {
                $ventasSabor = array();
        
                foreach ($ventas as $venta) {
                    if ($venta->getSabor() === $sabor) {
                        $ventasSabor[] = $venta;
                    }
                }
        
                if (!empty($ventasSabor)) {
                    Venta::ListarVentas($ventasSabor);
                } else {
                    echo "[No hay ventas realizadas para el sabor " . $sabor . "!]";
                }
            } else {
                echo "[Ocurrió un error al intentar abrir el archivo de ventas!]";
            }
        }

        public static function buscarYListarVentasEntreFechas($fechaInicio,$fechaFin,$ventas){
            if (!empty($ventas) && $ventas !== null) {
                $ventasFiltradas = array(); 
                foreach ($ventas as $venta) {
                    $fechaVenta = $venta->getFechaVenta();
                    if ($fechaVenta >= $fechaInicio && $fechaVenta <= $fechaFin) {
                        $ventasFiltradas[] = $venta;
                    }
                }
                usort($ventasFiltradas, function ($ventaA, $ventaB) {
                    return strcmp($ventaA->getSabor(), $ventaB->getSabor());
                });
        
                if (!empty($ventasFiltradas)) {
                    echo "Se vendieron: " . "\n";
                    Venta::ListarVentas($ventasFiltradas);
                } else {
                    echo "[No hubo ventas entre las fechas: " . $fechaInicio . " y " . $fechaFin;
                }
            } 
        }

        /**
         * Este metodo estatico me permitira listar las
         * ventas realizadas.
         * 
         * @param array el array de ventas.
         */
        public static function listarVentas($ventas){
            echo "<ul>";
            foreach($ventas as $venta){
                echo "<li>";
                echo "Fecha: " . $venta->getFechaVenta() . "<br>";
                echo "Usuario: " . $venta->getEmailUsuario() . "<br>";
                echo "Sabor: " . $venta->getSabor() . "<br>";
                echo "Tipo: " . $venta->getTipo() . "<br>";
                echo "Cantidad: " . $venta->getCantidad() . "<br>";
                echo "Total Venta: $" . $venta->getTotalVenta() . "<br>";
                echo "</li>";
            }
            echo "</ul>";
        }

        /**
         * Me permitira leer una venta de un archivo
         * JSON
         * 
         * @return array retorna el array de ventas
         * encontrado en el archivo json.
         */
        public static function leerJSON($jsonFile){
            $ventas = array();
            if(file_exists($jsonFile)){
                $archivo = fopen($jsonFile, "r");
                if ($archivo) {
                    $fileSize = filesize($jsonFile);
                    if ($fileSize > 0) {
                        $json = fread($archivo, $fileSize);
                        $ventasJson = json_decode($json, true);
                        foreach ($ventasJson as $venta) {
                            array_push($ventas, new Venta(
                                intval($venta["_idVenta"]), 
                                $venta["_emailUsuario"], 
                                $venta["_fechaVenta"], 
                                $venta["_sabor"],
                                $venta["_tipo"], 
                                floatval($venta["_totalVenta"]),
                                intval($venta["_cantidad"]),
                                intval($venta["_numeroPedido"]), 
                            ));
                        }
                    }
                    fclose($archivo);
                }
            }
            return $ventas;
        }

        /**
         * Me permitira guardar en un archivo json un 
         * array de ventas.
         * 
         * @param array el array de las ventas.
         * @param string la ruta donde se va a guardar.
         * 
         * @return bool true si pudo guardar, false sino.
         */
        public static function guardarJSON($ventas, $jsonFilename){
            $success = false;
            try {
                $file = fopen($jsonFilename, "w");
                if ($file) {
                    $json = json_encode($ventas, JSON_PRETTY_PRINT);
                    // var_dump($ventas);
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


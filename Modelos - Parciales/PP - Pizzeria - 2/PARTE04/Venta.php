<?php

    include_once "Pizza.php";
    class Venta{
//********************************************* ATRIBUTOS *********************************************
        public $_idVenta;
        public $_emailUsuario;
        public $_fechaVenta;
        public $_sabor;
        public $_tipo;
        public $_totalVenta;
        public $_cantidad;
        public $_imagenVenta;
        public $_numeroPedido;
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
        public function setImagen($imagen){
            if(!empty($imagen)) {
                $this->_imagenVenta= $imagen;}
        }
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
        public function getImagen(){
            return $this->_imagenVenta;
        }
        public function getNumeroPedido(){
            return $this->_numeroPedido;
        }
//********************************************* CONSTRUCTOR *********************************************
        public function __construct($id,$emailUsuario,$fechVenta,$sabor,$tipo,$totalVenta,$cantidad,$numeroPedido,$imagen = null)
        {
            $this->setID($id);
            $this->setEmailUsuario($emailUsuario);
            $this->setFechaVenta($fechVenta);
            $this->setSabor($sabor);
            $this->setTipo($tipo);
            $this->setTotalVenta($totalVenta);
            $this->setCantidad($cantidad);
            $this->setNumeroPedido($numeroPedido);
            $this->setImagen(($imagen !== null) ? $imagen : '');
        }
//********************************************* FUNCIONES *********************************************
        /**
         * Me permitirá eliminar una venta del archivo JSON.
         * 
         * @param int el numero de pedido a eliminar.
         * 
         * @return bool true si pudo eliminar correctamente,
         * false sino.
         */
        public static function EliminarVenta($numeroPedido){
            $ventas = Venta::LeerJSON('./archivos/ventas.json');
            // var_dump($ventas);
            $venta = Venta::BuscarVenta($ventas,$numeroPedido);
            // var_dump($venta);
            

            if($venta !== null){//-->Existe

                $rutaFoto = $venta->getImagen(); //-->Obtiene la ruta de la foto de la venta
                $nombreFoto = basename($rutaFoto); //-->Obtiene el nombre de la foto
                $directorioBackup = './BackupVentas/';
                $rutaBackup = $directorioBackup . $nombreFoto;

                if (rename($rutaFoto, $rutaBackup)) {//-->Muevo la foto
                    $key = array_search($venta,$ventas);//-->Busco su key para luego actualizarla
                    unset($ventas[$key]);//-->Con la key lo saco del array
                    if(Venta::GuardarJSON($ventas,'./archivos/ventas.json')){//-->Actualizo el JSON
                        return true;
                    }else {
                        //-->Si no guardo el json vuelve la imagen al lugar original
                        rename($rutaBackup, $rutaFoto);
                        return false;
                    }
                }
            }
            else
                return false;//-->No existe el numero de pedido.
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
        public static function ModificarVenta($numeroPedido,$sabor,$tipo,$cantidad,$emailUsuario){
            $ventas = Venta::LeerJSON('./archivos/ventas.json');
            $venta = Venta::BuscarVenta($ventas,$numeroPedido);
            if($venta !== null){
                $venta->setSabor($sabor);
                $venta->setCantidad($cantidad);
                $venta->setTipo($tipo);
                $venta->setEmailUsuario($emailUsuario);

                $key = array_search($venta,$ventas);//-->Busco su key para luego actualizarla

                $ventas[$key] = $venta;//-->Modifico en el array esa venta.

                if(Venta::GuardarJSON($ventas,'./archivos/ventas.json')){//-->Actualizo el JSON
                    return true;
                }
            }
            else
                return false;//-->No existe la venta
        }
        /**
         * Me permite generar una venta con las validaciones necesarias, 
         * verificar si hay stock, si puede guardar la imagen, si puede generarse.
         * 
         * @param object recibe un objeto Pizza
         * @param int cantidad a vender
         * @param string email del usuario
         * @param string imagen de la venta
         * 
         * @return bool true si pudo generar la venta, false sino.
         */
        public static function GenerarVenta($pizzaExistente,$cantidad,$emailUsuario,$imagen){
            $jsonFileVentas = './archivos/ventas.json';
            $ventas = Venta::LeerJSON($jsonFileVentas);

            if($pizzaExistente !== null){//-->Existe el producto
                if(Pizza::VerificarStock($pizzaExistente,$cantidad)){//-->Verifico si hay stock
                    $pizzaExistente->setCantidad($pizzaExistente->getCantidad() - $cantidad);
                    $totalVenta = $pizzaExistente->getPrecio() * $cantidad;

                    //-->Creo la nueva venta
                    $nuevaVenta = new Venta(
                    empty($ventas) ? 1 : (count($ventas) + 1),$emailUsuario,
                    (new DateTime('now'))->format('Y-m-d'),//-->Se guarda la fecha actual
                    $pizzaExistente->getSabor(),
                    $pizzaExistente->getTipo(),
                    $totalVenta,
                    $cantidad,
                    mt_rand(1,10000),
                    '');

                    $ventas[] = $nuevaVenta;

                    if(Venta::GenerarRutaImagenVenta($imagen,$emailUsuario,$pizzaExistente->getTipo(),$pizzaExistente->getSabor(),$nuevaVenta,$ventas)){
                        if(Venta::GuardarJSON($ventas,$jsonFileVentas)){
                            return true;
                        }
                        else
                            return false;
                    }
                    else
                        echo '[No se pudo generar la ruta de la imagen de la venta!]';
                }
                else
                    '[No hay stock para realizar la venta!]<br>';
            }
            else
                echo '[No existe la pizza requerida!]<br>';
        }

        /**
         * Esta funcion me permite retornar la ruta donde se guarda
         * la imagen de la venta, de esta forma se busca optimizar el codigo.
         * 
         * @param string imagen
         * @param string email del ususario
         * @param string tipo de producto
         * @param string sabor del producto
         * @param object la nueva venta generada
         * @param array el array de ventas (direccion en memoria)
         * 
         * @return bool retornara false si ocurrio un error al asignar
         * la imagen al producto, sino retorna true indicando que se pudo realizar.
         */
        public static function GenerarRutaImagenVenta($img, $email, $tipo, $sabor,$nuevaVenta,&$ventas) {
            $posicionArroba = strpos($email, "@");
            $stringFinal = substr($email, 0, $posicionArroba);
            $nombreImagen =  $tipo . '_' . $sabor . '_'
                . $stringFinal . '_' . (new DateTime('now'))->format('Y-m-d') . '.jpg';
            $directorioImagenesVenta = './ImagenesDeVentas/2023/';
            $rutaImagenVenta = $directorioImagenesVenta . $nombreImagen;
            var_dump($ventas);
        
            if ($img !== null) {
                if (move_uploaded_file($img['tmp_name'], $rutaImagenVenta)) {
                    $nuevaVenta->setImagen($rutaImagenVenta);
                    foreach ($ventas as &$v) {
                        if ($v->getID() === $nuevaVenta->getID()) {
                            $v->setImagen($nuevaVenta->getImagen());
                            return true;
                        }
                    }
                }
            }
            return false;
        }
        
        /**
         * Me permitira leer una venta de un archivo
         * JSON
         * 
         * @return array retorna el array de ventas
         * encontrado en el archivo json.
         */
        public static function LeerJSON($jsonFile){
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
                                $venta["_imagenVenta"],
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
        public static function GuardarJSON($ventas, $jsonFilename){
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

        public static function BuscarVenta($ventas,$numeroPedido){
            foreach ($ventas as &$venta) {
                if ($venta->getNumeroPedido() === $numeroPedido) {
                    return $venta;
                }
            }
            return null;
        }
//********************************************* BUSCAR Y LISTAR *********************************************
        /**
         * Me permite filtrar las ventas mediante 
         * el sabor requerido, imprimiendo una lista
         * como resultado.
         * 
         * @param string el sabor de la pizza a filtrar.
         */
        public static function BuscarVentaPorSabor($sabor){
            $json_file = './archivos/ventas.json';
            $ventas = Venta::LeerJSON($json_file);
        
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

        /**
         * Me permite filtrar el array de ventas y buscar por medio
         * de su email.
         * @param string el email del usuario a filtrar.
         */
        public static function BuscarVentaPorUsuario($emailUsuario){
            $json_file = './archivos/ventas.json';
            $ventas = Venta::LeerJSON($json_file);
        
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
         * Me permite buscar y listar ventas entre fechas indicadas
         * 
         * @param string la fecha de inicio de la buscqueda.
         * @param string la fehca de fin de la buscqueda.
         */
        public static function BuscarYListarVentasEntreFechas($fechaInicio,$fechaFin){
            $json_file = './archivos/ventas.json';
            $ventas = Venta::LeerJSON($json_file);
        
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
            } else {
                echo "[Ocurrió un error al buscar las ventas!]";
            }
        }

        /**
         * Me permite calcular el total de pizzas vendidas.
         * @return int||null int la cant de ventas totales,
         * null si no hay ventas.
         */
        public static function CalcularTotalPizzasVendidas() {
            $json_file = './archivos/ventas.json';
            $ventas = Venta::LeerJSON($json_file);
            
            if (!empty($ventas) && $ventas !== null) {
                $pizzasVendidas = 0;
                foreach ($ventas as $venta) {
                    $pizzasVendidas += $venta->getCantidad();
                }
                return $pizzasVendidas;
            } else {
                return null;
            }
        }
        
        /**
         * Este metodo estatico me permitira listar las
         * ventas realizadas.
         * 
         * @param array el array de ventas.
         */
        public static function ListarVentas($ventas){
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

    }
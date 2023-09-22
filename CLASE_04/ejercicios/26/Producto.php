<?php
/**
 * Aplicación Nº 26 (RealizarVenta)
 * 
 * Archivo: RealizarVenta.php
 * 
 * método:POST
 * 
 * Recibe los datos del producto(código de barra), del usuario (el id )y la cantidad de ítems ,por
 * POST .
 * Verificar que el usuario y el producto exista y tenga stock.
 * crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000). carga
 * los datos necesarios para guardar la venta en un nuevo renglón.
 * Retorna un :
 * “venta realizada”Se hizo una venta
 * “no se pudo hacer“si no se pudo hacer
 * Hacer los métodos necesaris en las clases
 * 
 * Bessio Rocio Soledad
 */
    class Producto{
        private $_codBarra;
        private $_id;
        private $_nombre;
        private $_tipo;
        private $_stock;
        private $_precio;
//----------------------------- Propiedades -----------------------------
        public function getCodigoBarra(){
            return $this->_codBarra;
        }

        public function getNombre(){
            return $this->_nombre;
        }

        public function getTipo(){
            return $this->_tipo;
        }

        public function getStock(){
            return $this->_stock;
        }

        public function getPrecio(){
            return $this->_precio;
        }

        public function getID(){
            return $this->_id;
        }
        
        public function set_Stock($value){
            $this->_stock = $value;
        }

//----------------------------- Constructor -----------------------------
        /**
         * Constructor de la clase Producto.
         * Verifico que los parametros no sea null
         * y que ademas la cadena del codigo de barra
         * cumpla con los requisitos.
         */
        public function __construct($codBarra, $nombre, $tipo, $stock, $precio,$id=null)
        {
            $this->_codBarra = $codBarra;
            $this->_nombre = $nombre;
            $this->_tipo = $tipo;
            $this->_stock = $stock;
            $this->_precio = $precio;
            if($id !== null){//-->Si id es distinto de null es producto existente
                $this->_id = $id;
            }
            else
                $this->_id = mt_rand(1,10000); // --> ID autoincremental
        }
        

//----------------------------- Funciones -----------------------------    
        /**
         * Funcion privada que me permitira
         * verificar que el codigo de barra tenga
         * 6 digitos.
         */
        private static function ValidarCodigoBarra($codBarra){
            $longitud = strlen($codBarra);
            return $longitud >= 6 && $longitud<= 6;
        }

        /**
         * Esta funcion recibe un array y el codigo
         * de barra de un producto.
         * Recorre el array en busca de la coincidencia
         * del codigo y otro producto.
         * Por ultimo lo retorna. Si no lo encuentra
         * quiere decir que no se encuentra y que debe de 
         * ser ingresado, por ende retorna null.
         */
        public static function BuscarProducto($productos, $codigoBarras) {
            foreach ($productos as $producto) {
                if (strtoupper($producto->getCodigoBarra()) === strtoupper($codigoBarras)) {
                    return $producto;
                }
            }
            return null;
        }
        

        /**
         * Este metodo me permitirá devolver
         * un array de productos.
         */
        public static function ObtenerProductos($file_name){
            $productos = array();
        
            if (file_exists($file_name)) {
                $contenido = file_get_contents($file_name);
                $data = json_decode($contenido, true);
                
                if ($data) {
                    foreach ($data as $productoData) {
                        $producto = new Producto(
                            strval($productoData['codBarra']),
                            $productoData['nombre'],
                            $productoData['tipo'],
                            $productoData['stock'],
                            $productoData['precio'],
                            $productoData['id']
                        );
                        $productos[] = $producto; 
                    }
                }
            }
            return $productos;
        }
        

        /**
         * Me permitirá guardar una instancia de
         * producto dentro de un archivo json.
         * 
         * #1: Me fijo si existe el archivo.
         * 
         * #2: Me fijo si ya existe registro en productos y los obtengo.
         * 
         * #2.1: En caso de existir lo actualizo agregandole al stock
         *       existente el stock que recibo.
         * 
         * #2.2: Para que no se repita en el array en el json 
         *       busco dentro del arry, obtengo la key y lo
         *       actualizo
         * 
         * #3: Si no existe entonces debo de crearlo.
         * 
         * #4: El array asociativo lo paso a formato JSON
         * 
         * #5: Guardo al producto en el archivo .json 
         */
        public static function AltaProducto($producto) {
            if ($producto instanceof Producto) {
                $file_name = "productos.json";
        
                $productos = array();
        
                //#3
                if (file_exists($file_name)) {
                    $content_json = file_get_contents($file_name);
                    $productos = json_decode($content_json, true);
                }
                
                //#2
                $codigoBarras = $producto->getCodigoBarra();
                $productoExistente = Producto::BuscarProducto($productos, $codigoBarras);
        
                if ($productoExistente !== null) {
                    //#2.1
                    $productoExistente['stock'] += $producto->getStock();
                    echo "[Actualizado!]";
        
                    //#2.2
                    $key = array_search($productoExistente, $productos);
                    $productos[$key] = $productoExistente;

                } else {
                    //#3
                    $prod = [
                        'id' => $producto->getID(),
                        'codBarra' => $codigoBarras,
                        'nombre' => $producto->getNombre(),
                        'tipo' => $producto->getTipo(),
                        'stock' => $producto->getStock(),
                        'precio' => $producto->getPrecio()
                    ];
                    $productos[] = $prod;
                    echo "[Ingresado!]";
                }
        
                $productosJSON = json_encode($productos, JSON_PRETTY_PRINT);
                //#4
                if (file_put_contents($file_name, $productosJSON)) {
                    return true;
                }
            }
            return false;
        }    

        /**
         * Esta funcion me permitira ver si
         * hay el stock suficiente para realizar la compra del 
         * producto.
         * 
         * #1: Retornará true or false si el stock de la instancia
         *     es mayor o igual a la cantidad que se requiere.
         */
        public function VerificarStock($cantidad){
             return $this->getStock() >= $cantidad;//#1
        }

        /**
         * Esta funcion me permite descontar
         * el stock de un producto.
         * 
         * #1: Obtengo los productos del json
         * 
         * #2: Busco el producto seleccionado.
         * 
         * #3: En caso de que no sea null actualizo el stock
         * 
         * #4: Guardo el archivo json nuevamente con la actualizacion de datos.
         */
        public static function DescontarStock($cantidad,$codBarra){
            $productos = Producto::ObtenerProductos("productos.json");//#1
            var_dump($productos);
            $productoSeleccionado = Producto::BuscarProducto($productos,$codBarra);//#2

            var_dump($productoSeleccionado);
            var_dump($codBarra);

            if($productoSeleccionado !== null){//#3
                $productoSeleccionado->set_Stock($productoSeleccionado->getStock() - $cantidad);

                $productos_JSON = json_encode($productos,JSON_PRETTY_PRINT);
                var_dump($productos_JSON);

                if(file_put_contents("productos.json",$productos_JSON)){//#4
                    echo "[Stock de producto actualizado!]";
                    return true;
                }
                else
                    return false;
            }
            else
                echo "[Producto no encontrado!]";
            return false;
        }
    }



?>
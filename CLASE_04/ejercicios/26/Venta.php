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
    include_once "Producto.php";
    include_once "Usuario.php";


    class Venta{
        private $_idVenta;
        private $_totalVenta;
        private $_cantidad;

//----------------------------- Propiedades -----------------------------  
        public function get_ID(){
            return $this->_idVenta;
        }

        public function set_ID($value){
            $this-> _idVenta = $value;
        }

        public function get_TotalVenta(){
            return $this->_totalVenta;
        }

        public function set_TotalVenta($value){
            $this->_totalVenta = $value;
        }

        public function get_Cantidad(){
            return $this->_cantidad;
        }

        public function set_Cantidad($value){
            $this->_cantidad= $value;
        }
//----------------------------- Constructor -----------------------------  
        /**
         * Constructor de la clase venta
         * Asigna un id random entre 1 y 10000
         */
        public function __construct($totalVenta = null,$cantidad = null)
        {
            if($totalVenta !== null && $cantidad !== null){
                $this->_totalVenta = $totalVenta;
                $this->_cantidad = $cantidad;
                $this->_idVenta = mt_rand(1,10000);
            }
        }

//----------------------------- Funciones -----------------------------  
        
        /**
         * La funcion permitira calcular el total de la venta realizada
         */
        private static function CalcularTotalVenta($producto,$cantidad){
            return $producto->getPrecio() * $cantidad;
        }

        /**
        * Esta funcion me permitira guardar una nueva venta
        * en un archivo json.
        * 
        * #1: Verifico que recibo una instancia de Venta.
        * 
        * #2: Me fijo si ya existe registro en ventas y las obtengo.
        * 
        * #3: El array asociativo lo paso a formato JSON
        * 
        * #4: Guardo al usuario en el archivo .json 
        */
        public static function GuardarVenta($venta){
            if($venta instanceof Venta){//#1
                $file_name = "ventas.json";

                $ventas = array();
                if(file_exists($file_name)){//#2
                    $contenido_json = file_get_contents($file_name);
                    $ventas = json_decode($contenido_json,true);
                }

                $ventaGuardar = [
                    'id' => $venta->get_ID(),
                    'cantidad' => $venta->get_Cantidad(),
                    'totalVenta'=>$venta->get_TotalVenta()
                ];

                $ventas[] = $ventaGuardar;

                //#3
                $ventas_json = json_encode($ventas,JSON_PRETTY_PRINT);
                //#4
                if(file_put_contents($file_name,$ventas_json)){
                    return true;
                }
                return false;
            }
            else
                return false;
        }

        /**
        * Esta funcion permitirá descontar la cantidad
        * soliciatada del stock.
        * 
        * #1: Primero obtengo la lista de productos y
        *     verfico que no venga vacia
        * 
        * #2: Mediante el id busco al usuario correspondiente.
        * 
        * #3: Busco al producto seleccionado pasandole el codBarra y 
        *     el array de productos.
        * 
        * #4: Como las funciones de busqueda pueden retornar null verifico
        *     que no lo haga, en caso de que alguna de las dos retorne null
        *      quiere decir que el usuario o el producto NO existen en el archivo.
        * 
        * #5: Con el metodo de instancia verifico si hay stock suficiente del producto
        *     sino lo hay lo aviso.
        * 
        * #6: Descuento el stock del producto.
        * 
        * #7: Guardo la venta generada con el total de la compra.
        */
        public static function RealizarVenta($codBarra,$cantidad,$idUsuario){
            $listaProductos = Producto::ObtenerProductos("productos.json");//#1

            if(!empty($listaProductos)){
                $usuario = Usuario::BuscarUsuario($idUsuario);//#2
                $productoSeleccionado = Producto::BuscarProducto($listaProductos,$codBarra);//#3

                //var_dump($usuario);
                var_dump($productoSeleccionado);
                
                //#4
                if($productoSeleccionado === null || $usuario === null){
                    echo "[producto o usuario inexistente, reintente!]";
                    return false;//-->Corto el flujo directamente
                }

                if(!$productoSeleccionado->VerificarStock($cantidad)){//#5
                    echo "[NO hay stock SUFICIENTE del producto seleccionado!]";
                    return false;//-->Corto el flujo del programa
                }

                if(!Producto::DescontarStock($cantidad,$productoSeleccionado->getCodigoBarra())){//#6
                    echo "[NO se pudo actualizar el stock del producto!]";
                    return false;//-->Corto el flujo del programa
                }

                //var_dump($productoSeleccionado);

                if(Venta::GuardarVenta(new Venta(Venta::CalcularTotalVenta($productoSeleccionado,$cantidad),$cantidad))){
                    echo "[Venta realizada con exito!]";
                    return true;
                }
                else{
                    echo "[Algo salio mal al intentar generar la venta!]";
                    return false;
                }
            }
            else
                echo "[No hay nada dentro de la lista de productos!]";
        }
    }
?>

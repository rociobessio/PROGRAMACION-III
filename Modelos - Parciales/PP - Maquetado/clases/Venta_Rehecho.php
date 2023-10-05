<?php


    class Venta_Rehecho implements JsonSerializable{
//***************************************************** ATRIBUTOS *****************************************************
        //$email,$nombreUsuario,$ProductoExistente,$cantidad,$total,$img=null,$importeFinal=null
        private $_id;
        private $_email;
        private $_nombreUsuario;
        private $_img;
        private $_total;
        private $_importeFinal;
        private $_cantidad;
        private $_producto;
        private $_numeroPedido;
        private $_fecha;
        private $_tipoProducto;
        private $_aderezoProducto;
        private $_nombreProducto;
//***************************************************** PROPIEDADES GETTERS *****************************************************
        public function getId(){
            return $this->_id;
        }
        public function getImagen(){
            return $this->_img;
        }
//***************************************************** PROPIEDADES SETTERS *****************************************************
        public function setImagen($value){
            $this->_img = $value;
        }
//***************************************************** CONSTRUCTOR *****************************************************
        public function __construct($email,$nombreUsuario,$ProductoExistente,$cantidad,$total,$numeroPedido,$img=null,$importeFinal=null) {
            $ventas = Archivo::ObtenerArray('./archivos/ventas.json');//-->traigo el array
            $this->_id = empty($ventas) ? 1 : (count($ventas) + 1);//-->Asigo id autoincremental.
            
            $this->_email = $email;
            $this->_nombreUsuario = $nombreUsuario;
            $this->_img = ($img !== null) ? $img : '';
            $this->_total = $total;
            $this->_importeFinal = isset($importeFinal) ? $importeFinal : $total;
            $this->_cantidad = $cantidad;
            $this->_producto = $ProductoExistente;
            $this->_numeroPedido = $numeroPedido;
            $this->_fecha = (new DateTime('now'))->format('Y-m-d');
            $this->_tipoProducto = $ProductoExistente['tipo'];
            $this->_aderezoProducto = $ProductoExistente['aderezo'];
            $this->_nombreProducto = $ProductoExistente['nombre'];
        }

//***************************************************** FUNCIONES *****************************************************
        public function jsonSerialize() {
            return [
                'id' => $this->_id, 
                'numeroPedido' => $this->_numeroPedido,
                'fecha' => $this->_fecha,
                'cantidad' => $this->_cantidad,
                'nombreProducto' => $this->_nombreProducto,
                'aderezoProducto' => $this->_aderezoProducto,
                'tipoProducto' => $this->_tipoProducto,
                'email' => $this->_email,
                'nombreUsuario' => $this->_nombreUsuario,
                'img' => $this->_img,
                'total' => $this->_total,
                'importeFinal' => $this->_importeFinal,
            ];
        }

        /**
         * Me permite buscar una venta en el
         * array de ventas mediante su numero
         * de pedido.
         * 
         * @param ventas el array de ventas
         * @param numeroPedido el numero de pedido
         * 
         * @return venta si existe, null sino.
         */
        public static function BuscarVenta($ventas,$numeroPedido){
            foreach ($ventas as &$venta) {
                if ($venta["numeroPedido"] === $numeroPedido) {
                    return $venta;
                }
            }
            return null;
        }

        /**
         * Función que genera una venta de un producto y la guarda en un archivo JSON.
         * 
         * @param email el email del usuario.
         * @param nombreUsuario el nombre del usuario.
         * @param ProductoExistente el producto.
         * @param cantidad la cantidad de la venta.
         * @param total el total de la venta.
         * @param img la imagen del producto, puede ser null.
         * @param importeTotal el importe total tras no tener descuento.
         * 
         * @return bool si la venta se realizó con éxito, true, sino false.
         */
        public static function GenerarVenta($email, $nombreUsuario, $ProductoExistente, $cantidad, $total, $img = null, $importeFinal = null) {
            $json_file = './archivos/ventas.json';
            $ventas = Archivo::ObtenerArray($json_file);
            //-->Creo la nueva venta
            $nuevaVenta = new Venta($email, $nombreUsuario, $ProductoExistente, $cantidad, $total, $img, $importeFinal);

            $ventas[] = $nuevaVenta;

            //-->Guardo las ventas
            $ventasJSON = json_encode($ventas, JSON_PRETTY_PRINT);

            if (file_put_contents($json_file, $ventasJSON)) {

                //-->Asigno la imagen
                $posicionArroba = strpos($email, "@");
                $stringFinal = substr($email, 0, $posicionArroba);
                $nombreImagen = $ProductoExistente['nombre'] . '_' . $ProductoExistente['tipo'] . '_' . $ProductoExistente['aderezo'] . '_' .  $stringFinal . '_' . (new DateTime('now'))->format('Y-m-d') . '.jpg';
                $directorioImagenesVenta = './ImagenesDeVentas/2023/';
                $rutaImagenVenta = $directorioImagenesVenta . $nombreImagen;

                if ($img !== null) {
                    if (move_uploaded_file($img['tmp_name'], $rutaImagenVenta)) {
                        // $nuevaVenta->setImagen($rutaImagenVenta); 

                        //--->La actualizo
                        foreach ($ventas as &$venta) {
                            if ($venta->getId() === $nuevaVenta->getId()) {
                                $venta->setImagen($nuevaVenta->getImagen());
                                break;
                            }
                        }
                    }
                }

                //-->Actualizo las ventas
                $ventasJSON = json_encode($ventas, JSON_PRETTY_PRINT);

                if (file_put_contents($json_file, $ventasJSON)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
            return true;
        }

    }
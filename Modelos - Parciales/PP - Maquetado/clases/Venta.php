<?php

    require_once "Archivo.php";

    /**
     * Esta clase me ayudará
     * con el manejo de las ventas.
     */
    class Venta{ 


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
         * Me permite validar si la fecha de 
         * vencimiento del cupon no ha expirado.
         * 
         * @param cupon el cupon a validar
         * @return bool true si es valido, false sino.
         */
        public static function ValidarFechaVencimiento($cupon){
            $fechaActual = new DateTime('now');
            //var_dump($fechaActual);
            $fechaVencimientoCupon = new DateTime($cupon['vencimiento']);
            if ($fechaActual->format('Y-m-d') > $fechaVencimientoCupon) {
                return true;
            } 
            else 
                return false;
        }

        /**
         * Funcion que devuelve el descuento calculado.
         * 
         * @param precioProducto el valor del producto.
         * @param cuponDescuento el cupon.
         * 
         * @return int el valor del descuento o 0 sino.
         */
        public static function CalcularDescuento($precioProducto,$cuponDescuento){
            if($precioProducto >= 0 && $cuponDescuento >=1){
                $descuento = ($precioProducto * $cuponDescuento['porcentajeDescuento']) / 100 ;
                return $descuento;
            }
            else
                return 0;
        }

        /**
         * Me permitirá buscar un cupon de descuento mediante
         * su ID.
         * 
         * @param cuponID el ID del cupon
         * 
         * @return cupon el cupon si existe, null sino.
         */
        public static function BuscarCuponDescuento($cuponID){
            $json_file = '../archivos/cupones.json';
            $cupones = Archivo::ObtenerArray($json_file);

            foreach ($cupones as &$cupon) {
                if ($cupon["id"] === $cuponID) {
                    return $cupon;
                }
            }
            return null;
        }

        /**
         * Funcion que me permitirá generar una venta de un 
         * producto y guardarla en un archivo json.
         * 
         * @param email el email del usuario.
         * @param nombreUsuario el nombre del usuario.
         * @param productoExistente el producto.
         * @param cantidad la cantidad del venta.
         * @param total el total de la venta.
         * @param img la imagen del producto, puede ser null.
         * @param importeTotal el importe total tras no tener descuento.
         * 
         * @return bool si pudo realizarse la venta true, sino false.
         */
        public static function GenerarVenta($email,$nombreUsuario,$ProductoExistente,$cantidad,$total,$img=null,$importeFinal=null){
            $json_file = './archivos/ventas.json';
            $ventas = Archivo::ObtenerArray($json_file);

            $nuevaVenta = [
                'id' => empty($ventas) ? 1 : (count($ventas) + 1),
                'fecha' => (new DateTime('now'))->format('Y-m-d'),
                'numero_pedido' => mt_rand(1,190000),
                'cantidad' => intval($cantidad),
                'nombre_producto' => $ProductoExistente['nombre'],
                'tipo' => $ProductoExistente['tipo'],
                'aderezo' => $ProductoExistente['aderezo'],
                'total' => $total,
                //-->Si importeFinal es null quiere decir que el importe es el orignal, por ende asigo el original
                'importe_Final' => isset($importeFinal) ? $importeFinal : $total,
                'email_usuario' => $email,
                'nombre_usuario' => $nombreUsuario,
                'imagen' => '',
            ];

            $ventas[] = $nuevaVenta;//-->Agrego la nueva al array

            //-->Guardo las ventas
            $ventasJSON = json_encode($ventas,JSON_PRETTY_PRINT);

            if(file_put_contents($json_file,$ventasJSON)){

                //-->Ahora guardo la imagen de la venta
                $posicionArroba = strpos($email, "@");
                $stringFinal = substr($email, 0, $posicionArroba);
                $nombreImagen = $ProductoExistente['nombre'] . '_' . $ProductoExistente['tipo'] . '_' . $ProductoExistente['aderezo'] . '_' .  $stringFinal . '_' . (new DateTime('now'))->format('Y-m-d') . '.jpg';
                $directorioImagenesVenta = './ImagenesDeVentas/2023/';
                $rutaImagenVenta = $directorioImagenesVenta . $nombreImagen;
                
                if($img !== null){
                    if (move_uploaded_file($img['tmp_name'], $rutaImagenVenta)) {
                        //--->Asigno la imagen
                        $nuevaVenta['imagen'] = $rutaImagenVenta;
    
                        //Actualizo la imagen en la venta generada.
                        foreach ($ventas as &$venta) {
                            if ($venta["id"] === $nuevaVenta["id"]) {
                                $venta["imagen"] = $nuevaVenta["imagen"];
                                break;
                            }
                        }
                }
    
                //--->Actualizo las ventas
                $ventasJSON = json_encode($ventas, JSON_PRETTY_PRINT);
        
                if (file_put_contents($json_file, $ventasJSON)) {
                    return true;
                }
                else
                {
                    return false;
                }
                } 
                else {
                    return false;
                }
                return true;
            }
            else
                return false;
        }
    }
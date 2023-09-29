<?php

    require_once "./clases/Venta.php";
    require_once "validaciones.php";

    /**
     * Parte 03 - Punto 06
     * 6- (2 pts.) DevolverHelado.php (por POST),
     * Guardar en el archivo (devoluciones.json y cupones.json):
     * a- Se ingresa el número de pedido y la causa de la devolución. El número de pedido debe existir, se ingresa una
     * foto del cliente enojado,esto debe generar un cupón de descuento(id, devolucion_id, porcentajeDescuento,
     * estado[usado/no usadol]) con el 10% de descuento para la próxima compra.
     */
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['numero_pedido']) && isset($_POST['causa_devolucion']) && isset($_FILES['imagen_cliente'])){

            $numeroPedido = intval($_POST['numero_pedido']);
            $causaDevolucion = $_POST['causa_devolucion'];
            $imagenCliente = $_FILES['imagen_cliente'];

            $json_file = './archivos/ventas.json';

            $ventas = Validaciones::ObtenerArrayProducto($json_file);//-->Traigo el array
            $directorio_imagenes = './ImagenesDevolucion/2023/';
            //var_dump($ventas);
            $ventaEncontrada = Venta::BuscarVenta($ventas,$numeroPedido);
            //var_dump($ventaEncontrada);

            if(count($ventas) > 0){
                if( $ventaEncontrada!== null){
                    $nombre_img = $numeroPedido . '_' . $causaDevolucion . '_' . uniqid() . '.jpg' ;  
                    $ruta_img = $directorio_imagenes . $nombre_img; 

                    //-->Genero el CUPON
                    $cupon = [
                        'id' => uniqid(),
                        'devolucion_id' => uniqid(),
                        'porcentajeDescuento' => 10,
                        'estado' => 'no usado',//-->Lo guardo como que no esta usado el cupon.
                    ];

                    $devoluciones = Validaciones::ObtenerArrayProducto('./archivos/devoluciones.json');
                    //var_dump($devoluciones);
                    
                    if($devoluciones !== null){
                        $nuevaDevolucion = [
                            'numero_pedido' => $numeroPedido,
                            'causa_devolucion' => $causaDevolucion,
                            'imagen_devolucion' => $ruta_img
                        ];

                        if(move_uploaded_file($imagenCliente['tmp_name'],$ruta_img)){
                            $nuevaDevolucion['imagen_devolucion'] = $ruta_img;//-->Asigno la imagen 
                        }

                        $devoluciones[] = $nuevaDevolucion;
                        //--->Guardo todo respectivamente:
                        file_put_contents('./archivos/devoluciones.json', json_encode($devoluciones, JSON_PRETTY_PRINT));
    
                        $cuponesJSON = file_get_contents('./archivos/cupones.json');
                        $cupones = json_decode($cuponesJSON, true);
                        $cupones[] = $cupon;//-->Guardo ese cupon en el array
                        file_put_contents('./archivos/cupones.json', json_encode($cupones, JSON_PRETTY_PRINT));

                        echo "[La devolucion fue generada correctamente. Se ha generado un cupón de descuento!]";
                    }
                }
                else
                    echo "[No hay ningun producto con ese número de pedido!]";
            }
            else
                echo "[No hay ventas registradas!]";

        }
        else
            echo "[Se necesitan ingresar todos los datos!]";
    }
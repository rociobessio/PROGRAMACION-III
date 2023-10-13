<?php

    require_once "./clases/Venta.php";
    require_once "./clases/Cupon.php";
    require_once "./clases/Devolucion.php";
    require_once "./Uploader.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['numeroPedido']) && isset($_POST['causaDevolucion']) && isset($_FILES['imagenCliente'])){
            $numeroPedido = intval($_POST['numeroPedido']);
            $causaDevolucion = $_POST['causaDevolucion'];
            $imagenCliente = $_FILES['imagenCliente'];

            $montonFinal = 1.0;//-->Aun no se calcula
            $descuento = 10;//--->Cada cupon tendra un descuento del 10%

            $jsonFileVentas = './archivos/ventas.json';
            $jsonFileDevoluciones = './archivos/devoluciones.json';
            $ventas = Venta::leerJSON($jsonFileVentas);
            $venta = Venta::buscarVenta($ventas,$numeroPedido);
            $idCupon = mt_rand(1,10000);

            if($venta !== null){//-->Existe le numero de pedido solicitado
                //-->Se genera el cupon de descuento y la devolucion.
                if(Cupon::generarCupon(new Cupon($idCupon,$numeroPedido,$venta->getEmailUsuario(),false,$descuento,$montonFinal),'crear') &&
                    Devolucion::generarDevolucion(new Devolucion(mt_rand(1,9000),$numeroPedido,$causaDevolucion,$idCupon),$jsonFileDevoluciones)){
                        echo json_encode(['SUCCESS' => 'Cupon y devolucion generados correctamente!']);        
                }
                else
                    echo json_encode(['ERROR' => 'No se ha podido generar la devolucion o el cupon!']);        
            }
            else
                echo json_encode(['ERROR' => 'Parece que no existe la venta con el numero de pedido solicitado!']);
        }
        else
            echo json_encode(['ERROR' => 'Se necesitan todos los parametros para seguir!']);
    }
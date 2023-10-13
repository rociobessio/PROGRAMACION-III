<?php

    include_once "./clases/Producto.php";
    include_once "./clases/Venta.php";
    include_once "./clases/Cupon.php";
    include_once "./clases/Devolucion.php";

    if($_SERVER['REQUEST_METHOD'] === 'GET'){

        if(isset($_GET['Consultar'])){
            switch ($_GET['Consultar']){
                //-->cantidad de pizzas vendidas en un día en particular, si no se pasa fecha, se muestran las del dia de hoy
                case 'CantPizzasVendidasEnDiaParticular':
                    //-->En caso de no recibir una fecha en particular se asignara la de hoy
                    $fecha = $_GET['fechaParticular'] ?? date("Y-m-d"); 
                    $totalPizzasVendidas = Venta::calcularTotalPizzasVendidas($fecha);
                    if ($totalPizzasVendidas !== null) {
                        echo "[En total se vendieron: " . $totalPizzasVendidas . " pizzas!]";
                    } else {
                        echo "[Ocurrió un error al abrir el archivo!]";
                    }
                break;
                //-->el listado de ventas entre dos fechas ordenado por sabor.
                case 'ListarVentasEntreFechasSabor':
                    if(isset($_GET['fechaInicio']) && isset($_GET['fechaFin']) ){ 
                        $fechaInicio = $_GET['fechaInicio'];
                        $fechaFin = $_GET['fechaFin'];
                        $json_file = './archivos/ventas.json';
                        $ventas = Venta::leerJSON($json_file);
                        Venta::buscarYListarVentasEntreFechas($fechaInicio,$fechaFin,$ventas);
                    }
                    else
                        echo "[Se necesitan las fechas para seguir!]";
                break;
                case 'ListarVentasUsuario'://-->el listado de ventas de un usuario ingresado
                    if(isset($_GET['emailUsuario'])){
                        $correoIngresado = $_GET['emailUsuario'];
                        $jsonFile = './archivos/ventas.json';
                        $ventas = Venta::LeerJSON($jsonFile);
                        Venta::buscarVentaPorUsuario($correoIngresado,$ventas);
                    }
                    else
                        echo "[Se necesita ingresar el email del usuario para seguir!]";
                break;
                case 'ListarVentasSabor'://-->el listado de ventas de un sabor ingresado
                    if(isset($_GET['sabor'])){
                        $sabor = $_GET['sabor'];
                        $jsonFile = './archivos/ventas.json';
                        $ventas = Venta::LeerJSON($jsonFile);
                        Venta::buscarVentaPorSabor($sabor,$ventas);
                    }
                    else
                        echo "[Se necesita el sabor de producto para seguir!]";
                break;
                default:
                    echo json_encode(['ERROR' => 'Accion CONSULTAR no permitida']);
                break;
            }
        } else {
            echo json_encode(['ERROR' => 'Falta el parametro Consultar']);
        }
    }
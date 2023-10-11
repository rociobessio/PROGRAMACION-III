<?php

    include_once "Pizza.php";
    include_once "Venta.php";

if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET['Consultar'])){
        switch ($_GET['Consultar']){
            case 'Cantidad_Pizzas_Totales_Vendidos'://-->Cant. TOTAL de productos vendidos
                $totalPizzasVendidas = Venta::CalcularTotalPizzasVendidas();
                if ($totalPizzasVendidas !== null) {
                    echo "[En total se vendieron: " . $totalPizzasVendidas . " pizzas!]";
                } else {
                    echo "[Ocurrió un error al abrir el archivo!]";
                }
            break;
            //-->el listado de ventas entre dos fechas ordenado por sabor.
            case 'Cantidad_Pizzas_Entre_Fechas'://-->Entre una fecha de inicio y otra de fin
                if(isset($_GET['fechaInicio']) && isset($_GET['fechaFin']) ){ 
                    $fechaInicio = $_GET['fechaInicio'];
                    $fechaFin = $_GET['fechaFin'];
                    Venta::BuscarYListarVentasEntreFechas($fechaInicio,$fechaFin);
                }
                else
                    echo "[Se necesita el tipo de producto para seguir!]";
            break;
            //-->c- el listado de ventas de un usuario ingresado
            case 'Ventas_Usuario':
                if(isset($_GET['emailUsuario'])){
                    $correoIngresado = $_GET['emailUsuario'];
                    Venta::BuscarVentaPorUsuario($correoIngresado);
                }
                else
                    echo "[Se necesita ingresar el email del usuario para seguir!]";
            break;
            //-->el listado de ventas de un sabor ingresado
            case 'Sabor_Pizza':
                if(isset($_GET['sabor'])){
                    $sabor = $_GET['sabor'];
                    Venta::BuscarVentaPorSabor($sabor);
                }
                else
                    echo "[Se necesita el tipo de producto para seguir!]";
            break; 
            default:
            echo json_encode(['error' => 'Metodo ACCION CONSULTAR no permitida']);
            break;
        }

    }

}
<?php
    include_once "./clases/Cupon.php";
    include_once "./clases/Devolucion.php";

    if($_SERVER['REQUEST_METHOD'] === 'GET'){

        if(isset($_GET['Consultar'])){
            switch ($_GET['Consultar']){
                case 'ListarDevolucionesConCuponesUsados'://-->C
                    $cupones = Cupon::leerJSON('./archivos/cupones.json');
                    $devoluciones = Devolucion::leerJSON('./archivos/devoluciones.json');
                    Devolucion::listarDevolucionesConCupones(Devolucion::obtenerDevolucionesConCuponesYEstados($cupones,$devoluciones));
                break;
                default:
                    echo json_encode(['ERROR' => 'Accion Consultar no permitida']);
                break;
            }
        }
    }
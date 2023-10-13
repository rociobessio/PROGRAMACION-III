<?php
    include_once "./clases/Cupon.php";
    include_once "./clases/Devolucion.php";

    if($_SERVER['REQUEST_METHOD'] === 'GET'){

        if(isset($_GET['Consultar'])){
            switch ($_GET['Consultar']){
                case 'ListarDevolucionesConCupones'://-->C
                    $cupones = Cupon::leerJSON('./archivos/cupones.json');
                    $devoluciones = Devolucion::leerJSON('./archivos/devoluciones.json');
                    Devolucion::listarDevolucionesConCupones(Devolucion::obtenerDevolucionesConCupones($cupones,$devoluciones));
                break;
                case 'ListarCuponesEstado'://-->b-Listar solo los cupones y su estado
                    $cupones = Cupon::leerJSON('./archivos/cupones.json');
                    $cuponesConEstado = Cupon::obtenerCuponesConEstado($cupones);
                    Cupon::listarCuponesEstado($cuponesConEstado);
                break;
                case 'ListarDevolucionesConCuponesYEstado':
                    $cupones = Cupon::leerJSON('./archivos/cupones.json');
                    $devoluciones = Devolucion::leerJSON('./archivos/devoluciones.json');
                    Devolucion::listarDevolucionesConCuponesYEstado(Devolucion::obtenerDevolucionesConCupones($cupones,$devoluciones));
                break;
                default:
                    echo json_encode(['ERROR' => 'Accion Consultar no permitida']);
                break;
            }
        }
    }
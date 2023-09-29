<?php

/**
 * index.php:Recibe todas las peticiones que realiza el postman, y administra a qué archivo se debe incluir.
 */

 switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        if(isset($_GET['accion'])){
            switch ($_GET['accion']) {
                case 'funcion':
                    //Incluir clases

                    //-->if(isset)
                break;
            }
        }
    break;

    case 'POST':
        if(isset($_POST['accion'])){
            switch ($_POST['accion']) {
                case 'funcion':
                    //Incluir clases

                    //-->if(isset)
                break;
            }
        }
    break;
    default:
        echo "[La request solicitada no esta permitida.]" . '<br>';
    break;
 }
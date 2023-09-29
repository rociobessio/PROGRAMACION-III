<?php
/**
 * Parte 01 - Punto 02
 * (1pt.) HeladoConsultar.php: (por POST) Se ingresa Sabor y Tipo, si coincide con algún registro del archivo
 * heladeria.json, retornar “existe”. De lo contrario informar si no existe el tipo o el nombre.
 */

    include_once "validaciones.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['sabor']) && isset($_POST['tipo'])){
            $sabor = $_POST['sabor'];
            $tipo = $_POST['tipo'];

            //-->Me traigo el array de helados
           $json_file = './archivos/heladeria.json';
           $helados = array();

           if(file_exists($json_file)){
            $contenido = file_get_contents($json_file);
            $helados = json_decode($contenido,true);
           }
           $existe = Validaciones::BuscarHelado($helados,$sabor,$tipo);
           //-->Me fijo 
           if ( $existe !== null){
                echo "[El helado YA EXISTE!]";
            }
            else{
                echo "[El helado no existe dentro de la heladeria!]";
            }
        }
        else
            echo "[Se deben de ingresar todos los datos.]";
    }

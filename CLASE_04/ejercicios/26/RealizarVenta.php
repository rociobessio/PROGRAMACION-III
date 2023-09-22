<?php
/**
 * Aplicación Nº 26 (RealizarVenta)
 * 
 * Archivo: RealizarVenta.php
 * 
 * método:POST
 * 
 * Recibe los datos del producto(código de barra), del usuario (el id )y la cantidad de ítems ,por
 * POST .
 * Verificar que el usuario y el producto exista y tenga stock.
 * crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000). carga
 * los datos necesarios para guardar la venta en un nuevo renglón.
 * Retorna un :
 * “venta realizada”Se hizo una venta
 * “no se pudo hacer“si no se pudo hacer
 * Hacer los métodos necesaris en las clases
 * 
 * Bessio Rocio Soledad
 */
    require_once "Venta.php";


    if($_SERVER['REQUEST_METHOD'] === 'POST'){//#1
    
        if (isset($_POST['codBarra']) && isset($_POST['idUsuario']) && isset($_POST['cantidad'])){
            $codigoBarra = $_POST['codBarra'];
            $idUsuario = $_POST['idUsuario'];
            $cantidad = $_POST['cantidad']; 

            $resultado = Venta::RealizarVenta($codigoBarra,$cantidad,$idUsuario);

            if($resultado){
                echo "<br>" . "[Todo OK!]";
            }
            else    
                {echo "<br>" . "[No se pudo realizar la venta!]";}
        } 
        else{
            echo "[Se necesitan todos los datos!]";
        }
    }
    else
        echo "[La request solicitada NO es del tipo POST. Reintente!]<br>";

?>


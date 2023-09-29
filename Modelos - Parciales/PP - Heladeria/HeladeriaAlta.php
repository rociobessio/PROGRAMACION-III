<?php
    require_once "validaciones.php";
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['sabor']) && isset($_POST['precio']) && isset($_POST['tipo']) &&
           isset($_POST['vaso']) && isset($_POST['stock']) && isset($_FILES['imagen'])){
           $sabor = $_POST['sabor'];
           $precio = floatval($_POST['precio']);//-->Convierto a flotante
           $tipo = $_POST['tipo'];
           $vaso = $_POST['vaso'];
           $stock = intval($_POST['stock']);//-->Convierto a entero
           $imagen = $_FILES['imagen'];

            //-->Valido el tipo y vaso ingresados.
           if(!Validaciones::validarTipo($tipo) || !Validaciones::validarVaso($vaso))
           {
               echo "[Vaso o tipo de helado incorrecto.]";
               exit;//-->Cierro
           }

           $directorio_imagenes = './ImagenesDeHelados/2023/';

           //-->Verifico si existe el helado dentro del json.
           $json_file = './archivos/heladeria.json';
           $helados = array();

           if(file_exists($json_file)){
            $contenido = file_get_contents($json_file);
            $helados = json_decode($contenido,true);
           }

           $heladoExistente = Validaciones::BuscarHelado($helados,$sabor,$tipo);

           //-->Logica para la alta o modificacion de stock:
            if($heladoExistente !== null){//-->Quiere decir que existe
                $heladoExistente["precio"] = $precio;
                $heladoExistente["stock"] += $stock;//-->Se actualiza el stock

                //-->Debo de actualizarlo en el array
                foreach ($helados as &$helado) {
                    if ($helado["id"] == $heladoExistente["id"]) {
                        $helado = $heladoExistente;
                        break;
                    }
                }
            }
            else{//-->No esta registrado
                $nuevoHelado = [ 
                    'id' => count($helados) + 1,//-->Simulacion de ID autoincremental
                    'sabor' => $sabor,
                    'tipo' => $tipo,
                    'vaso' => $vaso,
                    'precio' => $precio,
                    'stock' => $stock,
                    'imagen' => '',//-->Se guardara en un principio vacia para luego asignarla.
                ];

                //-->Creo la ruta para guardar la imagen del producto
                $nombre_img = $sabor . '_' . $tipo . '_' . uniqid() . '.jpg' ;  
                $ruta_img = $directorio_imagenes . $nombre_img;

                if(move_uploaded_file($imagen['tmp_name'],$ruta_img)){
                    $nuevoHelado['imagen'] = $ruta_img;//-->Asigno la imagen 
                }

                $helados[] = $nuevoHelado;//-->Agrego el nuevo helado al array
            }

            //-->Ahora guardo nuevamente el archivo:
            $heladosJSON = json_encode($helados,JSON_PRETTY_PRINT);

            if(file_put_contents($json_file,$heladosJSON)){
                echo "[Helado guardado correctamente!]";
            }
            else
                echo "[Ocurrio un error al querer guardar el helado!]";

        }
        else
            echo "[Se deben de ingresar todos los datos!]";
    }
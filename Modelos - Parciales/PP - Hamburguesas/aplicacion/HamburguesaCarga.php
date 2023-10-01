<?php

/**
 *                          PARTE 01
 * 
 * - (1 pt.) HamburguesaCarga.php: (por POST) se ingresa Nombre, Precio, Tipo (“simple” o “doble”), Aderezo
 * (“Mostaza”, “Mayonesa”, “Ketchup”) y Cantidad( de unidades). Se guardan los datos en en el archivo de texto
 * Hamburguesas.json, tomando un id autoincremental como identificador(emulado) .Sí el nombre y tipo ya existen
 * , se actualiza el precio y se suma al stock existente.
 * completar el alta con imagen de la hamburguesa, guardando la imagen con el tipo y el nombre como
 * identificación en la carpeta /ImagenesDeHamburguesas/2023.
 */
    require_once "../clases/Hamburguesa.php";   

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['nombre']) && isset($_POST['precio']) && isset($_POST['tipo']) && isset($_POST['aderezo']) &&
           isset($_POST['cantidad']) && isset($_FILES['imagen_hamburguesa'])){

            $nombre = $_POST['nombre'];
            $precio = floatval($_POST['precio']);
            $tipo = $_POST['tipo'];
            $aderezo = $_POST['aderezo'];
            $cantidad = intval($_POST['cantidad']);
            $imagen = $_FILES['imagen_hamburguesa'];

            if(!Hamburguesa::ValidarTipo($tipo) || !Hamburguesa::ValidarAderezo($aderezo)){
                echo "[Se debe de ingresar un tipo o aderezo valido!]";
                exit;
            }

            $directorio_imagenes = '../ImagenesDeHamburguesas/2023/';

            //-->Verifico si existe el helado dentro del json.
            $json_file = '../archivos/hamburguesas.json';
            $hamburguesas = array();
 
            if(file_exists($json_file)){
             $contenido = file_get_contents($json_file);
             $hamburguesas = json_decode($contenido,true);
            }

            $hamburguesaExistente = Hamburguesa::BuscarHamburguesa($hamburguesas,$nombre,$tipo);

            if($hamburguesaExistente !== null){//-->Quiere decir que existe
                $hamburguesaExistente["precio"] = $precio;
                $hamburguesaExistente["cantidad"] += $cantidad;//-->Se actualiza el stock

                //-->Debo de actualizarlo en el array
                foreach ($hamburguesas as &$hamburguesa) {
                    if ($hamburguesa["id"] == $hamburguesaExistente["id"]) {
                        $hamburguesa = $hamburguesaExistente;//-->La asigno
                        break;
                    }
                }
            }
            else{//-->No existe nuevo producto.
                $nuevoHamburguesa = [ 
                    //-->Simulacion de ID autoincremental, sino hay elementos en el array, lo asina para no romper.
                    'id' => count($hamburguesas) > 0 ? count($hamburguesas) + 1 : 1,
                    'nombre' => $nombre,
                    'tipo' => $tipo,
                    'aderezo' => $aderezo,
                    'precio' => $precio,
                    'cantidad' => $cantidad,
                    'imagen_hamburguesa' => '',//-->Se guardara en un principio vacia para luego asignarla.
                ];

                //-->Creo la ruta para guardar la imagen del producto
                $nombreimg = $nombre . '_' . $tipo . '_' . uniqid() . '.jpg' ;  
                $rutaImg = $directorio_imagenes . $nombreimg;

                if(move_uploaded_file($imagen['tmp_name'],$rutaImg)){
                    $nuevoHamburguesa['imagen_hamburguesa'] = $rutaImg;//-->Asigno la imagen 
                }

                $hamburguesas[] = $nuevoHamburguesa;//-->Agrego al array la nueva hamburguesa
            }

            //-->Ahora guardo nuevamente el archivo:
            $hamburguesasJSON = json_encode($hamburguesas,JSON_PRETTY_PRINT);

            if(file_put_contents($json_file,$hamburguesasJSON)){
                echo "[Hamburguesa guardado correctamente!]";
            }
            else
                echo "[Ocurrio un error al querer guardar la hamburguesa!]";

        }
        else
            echo "[Se necesitan todos los datos!]";
    }
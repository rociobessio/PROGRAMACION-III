<?php
/* ARCHIVOS 

        - fopen(abrir)    - fread/fgets(leer)         - copy(copia)        - fof(End of File)
        - fclose(cerrar)  - fwrite/fputs(escribit)    - unlink(eliminar)
*/
    //-->r solo lectura, r+ escritura y lectura, w+ escritura, lectura y se crea si no existe
    $archivo = fopen("explicacion.txt","r");//-->Abrir el archivo

    //-->Devuelve el contenido (string)
    echo fread($archivo,"r");//-->fgets lee linea por linea, y si tengo q leer todo entonces uso while.

    //-->Saber si llegue al fin del archivo
    while(!feof($archivo)){
        echo "<br>".fgets($archivo);
    }
    
    //-->Cerrarlo retorna true o false.
    fclose($archivo);

    //-->Escritura del archivo, debe ser ser abierto en forma de escritura "w":
    $archivo = fopen("explicacion.txt","w+");//-->Abrir el archivo

    echo fwrite($archivo,"Prueba de guardado");
    
    //-->Copiar el archivo
    echo copy("explicacion.txt","archivoCopia.txt");
    //-->Eliminar el archivo
    echo unlink("archivoCopia.txt");

    fclose($archivo);
?>
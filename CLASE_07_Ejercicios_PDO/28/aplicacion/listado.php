<?php

    /**
     * Aplicación No 28 ( Listado BD)
     * Archivo: listado.php
     * método:GET
     * Recibe qué listado va a retornar(ej:usuarios,productos,ventas)
     * cada objeto o clase tendrán los métodos para responder a la petición
     * devolviendo un listado en JSON
     * 
     * Bessio Rocio Soledad
     */

     include_once "./28/controllers/usuarioController.php";
     $usuarioController = new usuarioController();
     $usuarios = $usuarioController->listarUsuarios();
     echo json_encode($usuarios);
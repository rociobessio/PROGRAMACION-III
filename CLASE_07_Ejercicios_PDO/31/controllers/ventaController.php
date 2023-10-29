<?php
    require_once "./31/classes/Venta.php";
    class VentaController{
        
        public function realizarVenta($codBarra,$idUsuario,$cantidad){
            $venta = new Venta($codBarra,$idUsuario,$cantidad);
            // echo 'aca';
            return $venta->realizarVenta();
        }
    }
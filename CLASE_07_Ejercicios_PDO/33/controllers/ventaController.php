<?php
    require_once "./33/classes/Venta.php";
    class VentaController{
        
        /**
         * Me permitira llamar al metodo de Venta
         * para poder generar una nueva venta.
         */
        public function realizarVenta($codBarra,$idUsuario,$cantidad){
            $venta = new Venta($codBarra,$idUsuario,$cantidad);
            return $venta->realizarVenta();
        }
    }
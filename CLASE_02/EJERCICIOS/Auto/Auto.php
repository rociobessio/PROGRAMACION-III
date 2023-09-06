<?php
/*
    APLICACIÓN 17 - AUTO (POO)

    //Parte #1
    Realizar una clase llamada “Auto” que posea los siguientes atributos
    privados: _color (String)
              _precio (Double)
              _marca (String).
              _fecha (DateTime)
    //#2
    Realizar un constructor capaz de poder instanciar objetos pasándole como
    parámetros: i. La marca y el color.
                ii. La marca, color y el precio.
                iii. La marca, color, precio y fecha.

    #3:
    Realizar un método de instancia llamado “AgregarImpuestos”, que recibirá un doble
    por parámetro y que se sumará al precio del objeto.

    #4:
    Realizar un método de clase llamado “MostrarAuto”, que recibirá un objeto de tipo “Auto”
    por parámetro y que mostrará todos los atributos de dicho objeto.

    #5
    Crear el método de instancia “Equals” que permita comparar dos objetos de tipo “Auto”. Sólo
    devolverá TRUE si ambos “Autos” son de la misma marca.

    #6
    Crear un método de clase, llamado “Add” que permita sumar dos objetos “Auto” (sólo si son
    de la misma marca, y del mismo color, de lo contrario informarlo) y que retorne un Double con
    la suma de los precios o cero si no se pudo realizar la operación.

    #7
    Ejemplo: $importeDouble = Auto::Add($autoUno, $autoDos);

    Bessio Rocio Soledad
*/

    class Auto{
        //Atributos
        private $_color;
        private $_precio;
        private $_marca;
        private $_fecha;

        //Constructor ?:
        public function __construct($marca,$color,$precio = null, $fecha = null){
            $this->_marca = $marca;
            $this->_color = $color;
            $this->_precio = $precio;
            $this->_fecha = $fecha;
        }

        //Metodo de Instancia
        public function AgregarImpuestos($impuesto = 0){
            if(($impuesto > 0) && ($this->_precio !== null))
            { 
                $this->_precio += $impuesto;
            }
        }

        //Método de Clase:                  (Auto)?
        public static function MostrarAuto(Auto $auto = null){
            if($auto != null){
                echo "<br/>Marca: " . $auto->_marca . "<br/>";  
                echo "Color: " . $auto->_color . "<br/>";
                //Verifico que no sean null
                if($auto->_precio !== null)
                    echo "Precio: $" . $auto->_precio . "<br/>";
                if($auto->_fecha !== null)
                    echo "Fecha: " . $auto->_fecha->format('Y-m-d') . "<br/>";
            }
        }

        //Metodo de Instancia:
        public function Equals(Auto $auto_1,Auto $auto_2){
            if(!($auto_1 == null) && !($auto_2 == null)){//-->Verifico que no sean nulos
                return ($auto_1->_marca == $auto_2->_marca);//-->Si las marcas son iguales, devuelve true.
            }
        }

        //Metodo de clase
        public static function Add(Auto $auto_1,Auto $auto_2){ 
            if($auto_1->Equals($auto_1,$auto_2) && ($auto_1->_color == $auto_2->_color)){

                if($auto_1->_precio !== null && $auto_2->_precio !== null){
                    return $auto_1->_precio + $auto_2->_precio;//-->Se suman los precios
                }
            }
            else
            {
                echo "Los autos no pueden sumarse. No coinciden en marca y color!";
                return 0;
            }
        }
    }
?>
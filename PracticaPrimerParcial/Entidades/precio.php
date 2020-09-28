<?php

require_once './filemanager.php';

class precio
{

    public $_hora;
    public $_estadia;
    public $_mensual;


    public function __construct($hora, $estadia, $mensual)
    {
        $this->_hora = $hora;
        $this->_estadia = $estadia;
        $this->_mensual = $mensual;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __toString()
    {
        return $this->_id . '*' . $this->_nombre . '*' . $this->_cuatrimestre;
        //return json_encode($this);
    }


    public static function guardarprecio($hora, $estadia, $mensual){
        $precio = new precio($hora, $estadia, $mensual);
        filemanager::guardarJSON("precio.json", $precio);
    }

    public static function LeerPrecio()
    {
        $arrayJson = filemanager::LeerJSON('precio.json');
        $arrayPrecio = array();
        foreach ($arrayJson as $datos) {
            if (count((array)$datos) == 3) {
                $precioNuevo = new precio($datos->_hora, $datos->_estadia, $datos->_mensual);
                array_push($arrayPrecio, $precioNuevo);
            }
        }
        return $arrayPrecio;
    }
}

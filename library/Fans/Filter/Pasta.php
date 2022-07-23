<?php

class Fans_Filter_Pasta //implements Zend_Filter_Interface
{

    public function filter($value)
    {
        $value  = Fans_Filter_RemoverAcentos::filtrar($value);
        $value  = preg_replace("/[^A-Za-z0-9 _-]+/", "", $value);
        $value  = str_replace(' ', '_', $value);
        $value  = substr($value, 0, 20);

        return $value;
    }


    static function filtrar($value)
    {
        $filter = new Fans_Filter_Pasta();
        return $filter->filter($value);
    }
}
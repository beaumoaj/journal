<?php
/*
    Document   : Registry
    Created on : 12-Sep-2011, 20:37:59
    Author     : David Bennett
*/

class Registry
{
    private $vars = array();

    public function __set ( $key, $value )
    {
        $this->vars[$key] = $value;
    }

    public function __get ( $key )
    {
        return $this->vars[$key];
    }
    
    public function __exists( $key )
    {
        return array_key_exists( $key, $this->vars );
    }
}
?>

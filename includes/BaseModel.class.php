<?php
/*
    Document   : BaseModel
    Created on : 13-Sep-2011, 14:03:51
    Author     : David Bennett
*/

abstract class BaseModel
{
    protected $registry;

    public function __construct ( $registry )
    {
        $this->registry = $registry;
    }
}
?>

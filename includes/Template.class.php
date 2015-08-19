<?php
/*
    Document   : Template
    Created on : 12-Sep-2011, 20:38:23
    Author     : David Bennett
*/

class Template
{
    // Registry object
    private $registry;

    // Page variables
    private $vars = array();

    public function __construct ( $registry )
    {
        $this->registry = $registry;
    }

    public function __set ( $key, $value )
    {
        $this->vars[$key] = $value;
    }

    public function __get ( $key )
    {
        return $this->vars[$key];
    }

    public function show ( $view )
    {
        // Get the path to the view
        $path = __VIEW_PATH . '/' . $view . '.php';

        // Check the view exists
        if ( file_exists ( $path ) == false )
            die ( 'View not found: ' . $path );

        // Create variables with same names as keys
        foreach ( $this->vars as $key => $value )
            $$key = $value;

        // Make registry visible
        $registry = $this->registry;

        // Include the view
        include $path;
    }
}
?>

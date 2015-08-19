<?php
/*
    Document   : BaseController
    Created on : 12-Sep-2011, 20:37:49
    Author     : David Bennett
*/

abstract class BaseController
{
    protected $registry;

    public function __construct ( $registry )
    {
        $this->registry = $registry;
    }

    public abstract function index();

    protected function getModel ( $model, $action, $query = '' )
    {
        // Get the file
        $file = __MODEL_PATH . '/' . $model . 'Model.class.php';

        // 404 if model does not exist
        if ( is_readable ( $file ) == false )
            die ( 'File Not Found: ' . $file );

        // Include the model
        include_once $file;

        // Create an instance of the model
        $class = $model . 'Model';
        $model = new $class ( $this->registry );

        // Check action is callable
        if ( is_callable ( array ( $model, $action ) ) == false )
            throw new Exception ( 'Uncallable action: ' . $action );

        // Run the action and return the array
        return $model->$action ( $query );
    }
    
    protected function errorRedirect() {
        // Redirect
        header ( 'Location: ' . __SITE_DIR . '/error404' );
    }

}
?>

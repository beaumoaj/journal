<?php
/*
    Document   : loginController
    Created on : 14-Sep-2011, 00:30:59
    Author     : David Bennett
*/

class loginController extends BaseController
{
    public function index()
    {
        // Load index model
        $queryStr = array ( "username" => $_POST['username'], "password" => $_POST['password'] );
        $this->registry->template->model = $this->getModel ( 'user', 'login', $queryStr );

        // Redirect
        header ( 'Location: ' . __SITE_DIR . '/home' );
    }
}
?>

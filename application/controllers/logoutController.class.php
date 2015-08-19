<?php
/*
    Document   : logoutController
    Created on : 14-Sep-2011, 00:30:59
    Author     : David Bennett
*/

class logoutController extends BaseController
{
    public function index()
    {
        // Load index model
        $this->registry->template->model = $this->getModel ( 'user', 'logout' );

        // Redirect
        header ( 'Location: ' . __SITE_DIR . '/home' );
    }
}
?>

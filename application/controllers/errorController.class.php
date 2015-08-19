<?php
/*
    Document   : errorController
    Created on : 14-Sep-2011, 15:56:59
    Author     : David Bennett
*/

class errorController extends BaseController
{
    public function index() { }

    public function error404()
    {
        // Breadcrumbs
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add ( 'Home', '/home' );
        $breadcrumbs->add ( 'Error', '' );
        $this->registry->template->breadcrumbs = $breadcrumbs;
        
        // Load index model
        $this->registry->template->model = $this->getModel ( 'error', 'error404' );

        // Show the view
        $this->registry->template->show ( 'common/sidebarPage' );
    }
}
?>
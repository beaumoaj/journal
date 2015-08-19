<?php
/*
    Document   : homeController
    Created on : 12-Sep-2011, 22:34:59
    Author     : David Bennett
*/

class homeController extends BaseController
{
    public function index()
    {
        // Breadcrumbs
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add ( 'Home', '/home' );
        $this->registry->template->breadcrumbs = $breadcrumbs;
        
        // Load index model
        $this->registry->template->model = $this->getModel ( 'home', 'index' );

        // Show the view
        $this->registry->template->show ( 'home/home' );
    }
    
}
?>

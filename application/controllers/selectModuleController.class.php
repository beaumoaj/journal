<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of journalAdmin
 *
 * @author beaumoaj
 */
class selectModuleController extends BaseController {
    
    public function index() {
        // Breadcrumbs
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/home');
        $this->registry->template->breadcrumbs = $breadcrumbs;

        // Load index model
        $this->registry->template->model = $this->getModel('selectModule', 'index');

        // Show the view
        $this->registry->template->show('selectModule/smSelect');
    }

    public function selected() {
        // Load index model
        $queryStr = array("code" => addslashes($_GET['code']));
        $this->registry->template->model = $this->getModel('selectModule', 'selected', $queryStr);
        // Redirect
        header('Location: ' . __SITE_DIR . '/viewJournal');
    }

}

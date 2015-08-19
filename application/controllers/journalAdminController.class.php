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
class journalAdminController extends BaseController {

    public function index() {
        // Breadcrumbs
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/home');
        $this->registry->template->breadcrumbs = $breadcrumbs;

        // Load index model
        $this->registry->template->model = $this->getModel('journalAdmin', 'index');

        // Show the view
        $this->registry->template->show('journalAdmin/jaHome');
    }

    public function manage() {
        // Breadcrumbs
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/home');
        $this->registry->template->breadcrumbs = $breadcrumbs;

        // Load index model
        $this->registry->template->model = $this->getModel('journalAdmin', 'manage');

        // Show the view
        $this->registry->template->show('journalAdmin/jaManage');
    }

    public function create() {
        // Breadcrumbs
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/home');
        $this->registry->template->breadcrumbs = $breadcrumbs;

        // Load index model
        $this->registry->template->model = $this->getModel('journalAdmin', 'create');

        // Show the view
        $this->registry->template->show('journalAdmin/jaCreate');
    }

    public function createJournal() {
        // Load index model
        $queryStr = array("code" => $_POST['code'], "title" => $_POST['title'],
            "description" => $_POST['description'], "owner" => $_SESSION['username']);
        $this->registry->template->model = $this->getModel('journalAdmin', 'newJournal', $queryStr);

        // Redirect
        header('Location: ' . __SITE_DIR . '/journalAdmin');
    }

}

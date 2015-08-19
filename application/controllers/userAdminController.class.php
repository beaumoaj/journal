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
class userAdminController extends BaseController {

    public function index() {
        // Breadcrumbs
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/home');
        $this->registry->template->breadcrumbs = $breadcrumbs;


        // Show the view
        if (isset($_SESSION['username']) && isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
            // Load index model
            $this->registry->template->model = $this->getModel('userAdmin', 'index');

            $this->registry->template->show('userAdmin/index');
        } else {
            header('Location: ' . __SITE_DIR . '/home');
        }
    }

        public function taAdmin() {
        // Breadcrumbs
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/home');
        $this->registry->template->breadcrumbs = $breadcrumbs;


        // Show the view
        if (isset($_SESSION['username']) && isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
            // Load index model
            $this->registry->template->show('userAdmin/taAdmin');
        } else {
            header('Location: ' . __SITE_DIR . '/home');
        }
    }

    public function addUser() {
        // Load index model
        $queryStr = array("username" => addslashes($_POST['username']),
            "firstname" => addslashes($_POST['firstname']),
            "surname" => addslashes($_POST['surname']),
            "isTutor" => addslashes($_POST['isTutor']));
        $this->registry->template->model = $this->getModel('userAdmin', 'addUser', $queryStr);

        // Redirect
        header('Location: ' . __SITE_DIR . '/userAdmin');
    }

    public function addTA() {
        // Load index model
        $queryStr = array("username" => addslashes($_POST['user']),
            "moduleCode" => addslashes($_POST['module']));
        $this->registry->template->model = $this->getModel('userAdmin', 'addTA', $queryStr);

        // Redirect
        header('Location: ' . __SITE_DIR . '/userAdmin');
    }

    public function getUsers() {
        header('Content-Type: application/json');
        echo json_encode($this->getModel('userAdmin', 'getUsers'));
    }

    public function getTutors() {
        header('Content-Type: application/json');
        echo json_encode($this->getModel('userAdmin', 'getTutors'));
    }

    public function getModules() {
        header('Content-Type: application/json');
        echo json_encode($this->getModel('userAdmin', 'getModules'));
    }

    public function addModule() {
        // Load index model
        $queryStr = array("code" => $_POST['code'],
            "title" => $_POST['title'],
            "owner" => $_POST['owner']);
        $this->registry->template->model = $this->getModel('userAdmin', 'addModule', $queryStr);

        // Redirect
        header('Location: ' . __SITE_DIR . '/userAdmin');
    }

    public function studentUpload() {
        $queryStr = array("code" => $_POST['module']);
        $this->registry->template->model = $this->getModel('userAdmin', 'studentUpload', $queryStr);

        header('Location: ' . __SITE_DIR . '/userAdmin');
    }

}

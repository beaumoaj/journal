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
class tutorViewJournalController extends BaseController {

    public function index() {
        if (isset($_SESSION['tutor']) || isset($_SESSION['ta'])) {
            // Breadcrumbs
            $breadcrumbs = new Breadcrumbs();
            $breadcrumbs->add('Home', '/home');
            $this->registry->template->breadcrumbs = $breadcrumbs;

            // Load index model
            $this->registry->template->model = $this->getModel('tutorViewJournal', 'index');

            // Show the view
            $this->registry->template->show('tutorViewJournal/index');
        }
    }

    public function viewJournal() {
        if (isset($_GET['username'])) {
            $queryStr = array("username" => addslashes($_GET['username']));
        } else {
            $queryStr = array();
        }
        // Breadcrumbs
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/home');
        $this->registry->template->breadcrumbs = $breadcrumbs;

        // Load index model
        $this->registry->template->model = $this->getModel('tutorViewJournal', 'view', $queryStr);

        // Show the view
        $this->registry->template->show('tutorViewJournal/index');
    }

    public function viewEntry() {
        if (isset($_GET['entryId'])) {
            $queryStr = array("entryId" => intval(addslashes($_GET['entryId'])));
        } else {
            $queryStr = array();
        }
        // Breadcrumbs
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/home');
        $this->registry->template->breadcrumbs = $breadcrumbs;

        // Load index model
        $this->registry->template->model = $this->getModel('tutorViewJournal', 'viewEntry', $queryStr);

        // Show the view
        $this->registry->template->show('tutorViewJournal/viewEntry');
    }

    public function getClassifications() {
        if (isset($_SESSION['tutor']) || isset($_SESSION['ta'])) {
            // Load index model
            $this->registry->template->model = $this->getModel('tutorViewJournal', 'getClassifications');

            // Show the view (returns a JSON file)
            $this->registry->template->show('tutorViewJournal/getClassifications');
        }
    }

}

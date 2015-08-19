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
class viewJournalController extends BaseController {

    public function index() {
        if (isset($_SESSION['tutor']) || isset($_SESSION['ta'])) {
            // Redirect
            header('Location: ' . __SITE_DIR . '/tutorViewJournal');
        } else {
            // Breadcrumbs
            $breadcrumbs = new Breadcrumbs();
            $breadcrumbs->add('Home', '/home');
            $this->registry->template->breadcrumbs = $breadcrumbs;

            // Load index model
            $this->registry->template->model = $this->getModel('viewJournal', 'index');

            // Show the view
            $this->registry->template->show('viewJournal/vjHome');
        }
    }

    public function newEntry() {
        if (isset($_SESSION['username'])) {
            $breadcrumbs = new Breadcrumbs();
            $breadcrumbs->add('Home', '/home');
            $this->registry->template->breadcrumbs = $breadcrumbs;
            $datetime = date('Y/m/d H:i:s');
            $queryStr = array();
            $queryStr['dateTime'] = $datetime;
            $queryStr['author'] = $_SESSION['username'];
            $queryStr['journal'] = intval($_SESSION['currentJournal']);
            //error_log("{$_SERVER['REQUEST_METHOD']} NEW ENTRY author:{$queryStr['author']} journal:{$queryStr['journal']}");
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // create a scratch entry if it doesnt exist
                // datetime field is set to the current datetime value
                // return the fields of the new entry (or possibly existing entry)
                // Load index model
                $this->registry->template->model = $this->getModel('viewJournal', 'newScratch', $queryStr);
                // Show the view
                $this->registry->template->show('viewJournal/newEntryForm_1');
            } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // update the scratch entry with the new field(s)
                // datetime field contains the new time (last updated)
                // Load index model
                $request_body = file_get_contents('php://input');
                //error_log("PAYLOAD IS {$request_body}");
                $data = json_decode($request_body, true);
                //$data = json_decode($data[0]);
                //error_log("data is " . $data . " type " . gettype($data));
                $js = json_decode($data, true);
                //error_log("js  type " . gettype($js) . " length " . count($js));
                if (isset($js['id'])) {
                    //error_log("id is {$js['id']}");
                    $queryStr['id'] = intval($js['id']);
                } else {
                    //error_log("no id");
                }
                $sections = array('title', 'description', 'reflection', 'concepts', 'whatNext', 'referenceList', 'notes');
                foreach ($sections as $section) {
                    if (isset($js[$section])) {
                        //error_log("Found section update for " . $section);
                        $queryStr[$section] = $js[$section];
                    }
                }
                $this->registry->template->model = $this->getModel('viewJournal', 'updateScratch', $queryStr);
                // keep existing view in this case
            }
        }
    }

    public function commitEntry() {
        if (isset($_SESSION['username'])) {
            if (!isset($_POST['id'])) {
                //error_log("Copy the thing over");
                $datetime = date('Y/m/d H:i:s');
                $queryStr = array();
                $queryStr['dateTime'] = $datetime;
                $queryStr['author'] = $_SESSION['username'];
                $queryStr['journal'] = intval($_SESSION['currentJournal']);
            }
            $this->registry->template->model = $this->getModel('viewJournal', 'copyEntry', $queryStr);
            // shift the scratch entry into the JournalEntries table
            // then show the view
            // Redirect
            header('Location: ' . __SITE_DIR . '/viewJournal');
        }
    }

    /*
    public function updateEntry() {
        // Load index model
        $datetime = date('Y/m/d H:i:s');
        $queryStr = array("id" => $_POST['id'],
            "title" => $_POST['title'], "description" => $_POST['description'],
            "author" => $_SESSION['username'], "journal" => intVal($_SESSION['currentJournal']),
            "reflection" => $_POST['reflection'], "concepts" => $_POST['concepts'],
            "whatNext" => $_POST['whatNext'], "referenceList" => $_POST['referenceList'],
            "notes" => $_POST['notes'], "dateTime" => $datetime);
        $this->registry->template->model = $this->getModel('viewJournal', 'updateEntry', $queryStr);

        // Redirect
        header('Location: ' . __SITE_DIR . '/viewJournal');
    }
     * 
     */

    public function editEntry() {
        if (isset($_GET['entryId'])) {
            $queryStr = array("id" => intVal(addslashes($_GET['entryId'])));
        } else {
            $queryStr = array();
        }
        // Breadcrumbs
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/home');
        $this->registry->template->breadcrumbs = $breadcrumbs;

        // Load index model
        $this->registry->template->model = $this->getModel('viewJournal', 'edit', $queryStr);

        // Show the view
        $this->registry->template->show('viewJournal/newEntryForm_1');
    }

    public function viewEntry() {
        if (isset($_GET['entryId'])) {
            $queryStr = array("id" => intVal(addslashes($_GET['entryId'])));
        } else {
            $queryStr = array();
        }
        // Breadcrumbs
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/home');
        $this->registry->template->breadcrumbs = $breadcrumbs;

        // Load index model
        $this->registry->template->model = $this->getModel('viewJournal', 'edit', $queryStr);

        // Show the view
        $this->registry->template->show('viewJournal/viewEntry');
    }

}

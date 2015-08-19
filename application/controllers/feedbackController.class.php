<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of feedbackController
 *
 * @author beaumoaj
 */
class feedbackController extends BaseController {

  //put your code here
  public function index() {
    if (isset($_SESSION['tutor']) || isset($_SESSION['ta'])) {
      // Load index model
      $this->registry->template->model = $this->getModel('feedback', 'index');
      // Show the view
      $this->registry->template->show('feedback/index');
    }
  }

  public function count() {
    if (isset($_SESSION['tutor']) || isset($_SESSION['ta'])) {
      if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['journal']) && isset($_GET['student'])) {
          $queryStr = array();
          $queryStr["journal"] = $_GET['journal'];
          $queryStr["student"] = $_GET['student'];
          $queryStr["username"] = $_SESSION['username'];
          $this->registry->template->model = $this->getModel('feedback', 'count', $queryStr);
          $this->registry->template->show('classify/countIcon');
        }
      }
    } else {
      error_log("Could not generate a count Icon");
    }
  }

  public function entries() {
    if (isset($_SESSION['tutor']) || isset($_SESSION['ta'])) {
      if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $view = 'classify/getEntry';
        if (isset($_GET['journal']) && isset($_GET['author'])) {
          //error_log("journal and author");
          $queryStr = array();
          $queryStr["journal"] = $_GET['journal'];
          $queryStr["author"] = $_GET['author'];
        } else if (isset($this->registry->router->queryStr) &&
          is_array($this->registry->router->queryStr) &&
          count($this->registry->router->queryStr) == 1 &&
          is_numeric($this->registry->router->queryStr[0])) {
          $queryStr = array("id" => $this->registry->router->queryStr[0]);
          $view = 'classify/getClassification';
        } else {
          $queryStr = array();
        }
        $this->registry->template->model = $this->getModel('classify', 'entries', $queryStr);

        // Show the view
        $this->registry->template->show($view);
      } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $request_body = file_get_contents('php://input');
        //error_log("PAYLOAD IS {$request_body}");
        $data = json_decode($request_body, true);
        //error_log("classification is {$data['classification']}");
        //error_log("entry is {$data['entry']}");
        if (isset($data['classification']) && isset($data['entry']) &&
          (is_numeric($data['classification']) ||
          ($data['classification'] == '')) &&
          is_numeric($data['entry'])) {
          $queryStr = array();
          $queryStr["classification"] = $data['classification'];
          $queryStr["entry"] = $data['entry'];
          $queryStr["username"] = $_SESSION['username'];
        } else {
          $queryStr = null;
        }
        $this->registry->template->model = $this->getModel('feedback', 'entries', $queryStr);

        // there is no view
      }

      // Load index model
    }
  }

  public function comments() {
    //error_log("COMMENTS");
    if (isset($_SESSION['tutor']) || isset($_SESSION['ta'])) {
      if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        //error_log("COMMENTS GET");
        if (isset($this->registry->router->queryStr) &&
          is_array($this->registry->router->queryStr) &&
          count($this->registry->router->queryStr) == 1 &&
          is_numeric($this->registry->router->queryStr[0])) {
          $queryStr = array("id" => $this->registry->router->queryStr[0]);
          $view = 'classify/getComment';
        } else {
          $queryStr = array();
        }
        $this->registry->template->model = $this->getModel('feedback', 'comments', $queryStr);

        // Show the view
        $this->registry->template->show($view);
      } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //error_log("COMMENTS POST");
        $request_body = file_get_contents('php://input');
        //error_log("PAYLOAD IS {$request_body}");
        $data = json_decode($request_body, true);
        //error_log("classification is {$data['classification']}");
        //error_log("entry is {$data['entry']}");
        if (isset($data['comment']) && isset($data['entry']) &&
          is_numeric($data['entry'])) {
          $queryStr = array();
          $queryStr["comment"] = $data['comment'];
          $queryStr["entry"] = $data['entry'];
          $queryStr["username"] = $_SESSION['username'];
        } else {
          $queryStr = null;
        }
        $this->registry->template->model = $this->getModel('feedback', 'comments', $queryStr);

        // there is no view
      }

      // Load index model
    }
  }

}

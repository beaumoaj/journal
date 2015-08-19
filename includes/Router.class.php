<?php

/*
  Document   : Router
  Created on : 12-Sep-2011, 20:38:09
  Author     : David Bennett
 */

class Router {

    // Registry object
    private $registry;
    // The controller php file
    private $file;
    // Controller
    public $controller;
    // Action name
    public $action;
    // Query string
    public $queryStr;

    public function __construct($registry) {
        $this->registry = $registry;
    }

    public function run() {
        // Get the controller
        $this->getController();

        // Create the controller
        $controller = $this->createController();

        // 404 if invalid action
        if (is_callable(array($controller, $this->action)) == false)
            $controller = $this->set404();

        // Run the action
        $action = $this->action;
        $controller->$action();
    }

    private function getController() {
        error_log("------------------------------------------------------------------");
        $errorMsg = "";
        // Get the page
        $page = empty($_GET['rt']) ? 'home' : $_GET['rt'];
        //if (is_array($page)) {
        //    error_log("PAGE: " . implode('#', $page));
        //} else {
        //    error_log("PAGE: " . $page);
        //}
        // Get the controller
        $parts = explode('/', $page);
        //if (is_array($parts)) {
        //    error_log("PAGE PARTS: " . implode(' # ', $parts));
        //}
        $this->controller = $parts[0];
        //error_log("Controller: " . $this->controller);
        // Shift out the controller name
        array_shift($parts);

        // Get the action if one is provided
        if (isset($parts[0]))
            $this->action = $parts[0];
        else
            $this->action = 'index';
        //error_log("Action: " . $this->action);
        // Shift out the action
        array_shift($parts);
        //error_log(" PARTS ARE " . implode("#", $parts));
        // Remaining is query string
        $this->queryStr = $parts;
        /*
        if (isset($this->queryStr) && is_array($this->queryStr)) {
            error_log("Query String array length(" . count($this->queryStr) . ")");
            if (count($this->queryStr) > 0) {
                error_log(" first is " . $this->queryStr[0]);
            } else {
                error_log("QS is EMPTY ");
            }
        } else {
            error_log("Query String not array: {$this->queryStr}");
        }
        */
        // Get the file
        $this->file = __CONTROLLER_PATH . '/' . $this->controller . 'Controller.class.php';

        // Set controller to 404 if controller file not found
        if (is_readable($this->file) == false)
            $this->set404();
        error_log("Controller: {$this->controller}, Action: {$this->action}");
    }

    private function createController() {
        // Die if controller does not exist - this should never occur as a 404 page should be set
        if (is_readable($this->file) == false)
            die('Controller does not exist: ' . $this->controller . " file " . $this->file);

        // Include the controller
        include_once $this->file;

        // Create an instance of the controller
        $class = $this->controller . 'Controller';
        $controller = new $class($this->registry);

        return $controller;
    }

    private function set404() {
        $this->controller = 'error';
        $this->action = 'error404';
        $this->queryStr = '';
        $this->file = __CONTROLLER_PATH . '/' . 'errorController.class.php';

        return $this->createController();
    }

}

?>

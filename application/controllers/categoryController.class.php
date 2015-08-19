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
class categoryController extends BaseController {

    public function index() {
        if (isset($_SESSION['tutor']) || isset($_SESSION['ta'])) {
            // Load index model
            $this->registry->template->model = $this->getModel('category', 'index');

            // Show the view
            $this->registry->template->show('category/getClassifications');
        }
    }
    
    public function test() {
        if (isset($_SESSION['tutor']) || isset($_SESSION['ta'])) {
            // Load index model
            //$this->registry->template->model = $this->getModel('category', 'index');

            // Show the view
            $this->registry->template->show('category/index');
        }
        
    }

}

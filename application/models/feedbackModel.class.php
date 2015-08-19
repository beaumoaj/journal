<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of feedbackModel
 *
 * @author beaumoaj
 */
class feedbackModel extends BaseModel {

  //put your code here

  public function index() {
    $model = new Registry();
    $module = $_SESSION['selectedModule'];
    $journal = $_SESSION['currentJournal'];
    $studentList = $this->registry->db->select("UserToModule", "code=?", array($module));
    $model->studentList = $studentList;
    $classifications = $this->registry->db->select("classifications order by value asc");
    $model->classifications = $classifications;
    $model->title = "Feedback Page";
    $model->sidebarContent = "<table class=\"journal\"><tr><th>Student</th>" .
      "<th style='min-width:15px;'>&nbsp;</th>";
    foreach ($studentList as $student) {
      $model->sidebarContent .=
        "<tr class='notSelected' " .
        "onclick='getEntries(\"{$journal}\", " .
        "\"{$student['username']}\");' " .
        "id='row{$student['username']}'>";
      $model->sidebarContent .=
        "<td><div  class='editable' " .
        "id='cell{$student['username']}'>" .
        "{$student['username']}</div></td>" .
        "<td id='status{$student['username']}'>" .
        "<img class='autoResizeImage' " .
        "id='img{$student['username']}' " .
        "src='/feedback/count?journal={$journal}&"
        . "student={$student['username']}'/></td></tr>";
    }
    $model->sidebarContent .= "</table>";
    return $model;
  }
  
  public function count($queryStr) {
    $model = new Registry();
    if (isset($queryStr['journal']) &&
      isset($queryStr['student']) &&
      isset($queryStr['username'])) {
      $sql = "SELECT count(id) FROM journal.JournalEntries where author= ? AND journal= ?";
      $bind = array($queryStr['student'], $queryStr['journal']);
      $result1 = $this->registry->db->run($sql, $bind);
      $total = $result1[0]['count(id)'];
      $sql2 = "SELECT count(idGrade) FROM Grade, " .
        "JournalEntries where JournalEntries.author= ? AND " .
        "Grade.entryId=JournalEntries.id AND " .
        "Grade.username= ?";
      $bind = array($queryStr['student'], $queryStr['username']);
      $result2 = $this->registry->db->run($sql2, $bind);
      $done = $result2[0]['count(idGrade)'];
      $model->total = $total;
      $model->done = $done;
      // see http://upload.wikimedia.org/wikipedia/commons/6/65/Harveyballs.v2.svg
    }
    return $model;
  }

}

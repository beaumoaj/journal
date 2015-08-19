<?php

/*
  Document   : indexModel
  Created on : 12-Sep-2011, 22:34:59
  Author     : David Bennett
 */

class classifyModel extends BaseModel {

  private function truncate($str) {
    return (strlen($str) > 13) ? substr($str, 0, 10) . '...' : $str;
  }

  public function index() {
    $model = new Registry();
    $module = $_SESSION['selectedModule'];
    $journal = $_SESSION['currentJournal'];
    $studentList = $this->registry->db->select("UserToModule", "code=?", array($module));
    $model->studentList = $studentList;
    $classifications = $this->registry->db->select("classifications order by value asc");
    $model->classifications = $classifications;
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
        "src='/classify/count?journal={$journal}&"
        . "student={$student['username']}'/></td></tr>";
    }
    $model->sidebarContent .= "</table>";
    return $model;
  }

  public function comments($queryStr) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $model = new Registry();
      $table = "classified_comment";
      $bind = null;
      if (isset($queryStr['id'])) {
        $where = "entry= ? AND username= ?";
        $bind = array($queryStr['id'], $_SESSION['username']);
      } else {
        $where = "";
      }
      //error_log("CLASSIFY MODEL: where " . $where);
      $result = $this->registry->db->select($table, $where, $bind);
      $model->entries = $result;
      return $model;
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      //error_log("NEW CLASSIFICATION");
      if ($queryStr != null && isset($queryStr['comment']) &&
        isset($queryStr['entry']) && isset($queryStr['username'])) {
        $where = "entry= ? AND username= ?";
        $bind = array($queryStr['entry'], $queryStr['username']);
        $result = $this->registry->db->select("classified_comment", $where, $bind);
        if (count($result) > 0) {
          // update
          $where = "entry= :entry AND username= :username";
          $bind = array("entry" => $queryStr['entry'],
            "username" => $queryStr['username']);
          if ($queryStr['comment'] == "") {
            //error_log("DELETE classification");
            $rowCount = $this->registry->db->delete("classified_comment", $where, $bind);
          } else {
            $rowCount = $this->registry->db->update("classified_comment", array("comment" => $queryStr['comment']), $where, $bind
            );
          }
          //error_log("UPDATE CLASSIFICATION ({$rowCount})");
        } else if ($queryStr['comment'] != "") {
          // insert
          $rowCount = $this->registry->db->insert("classified_comment", array(
            "id" => "DEFAULT(id)",
            "comment" => $queryStr['comment'],
            "entry" => $queryStr['entry'],
            "username" => $queryStr['username']
          ));
        }
      } else {
        //error_log("NEW COMMENT: QS is NULL");
      }
    }
  }

  public function entries($queryStr) {

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $model = new Registry();
      $table = "JournalEntries";
      $bind = null;
      if (isset($queryStr['id'])) {
        //error_log("CLASSIFY MODEL: get entry with ID " . $queryStr['id']);
        $table = "classified_entries";
        $where = "entry= ? AND username= ?";
        $bind = array($queryStr['id'], $_SESSION['username']);
      } else if (isset($queryStr['journal']) && isset($queryStr['author'])) {
        //error_log("CLASSIFY QUERY journal " .
        //        $queryStr['journal'] .
        //        " & author " .
        //        $queryStr['author']);
        $where = "journal= ? AND author= ?";
        $bind = array($queryStr['journal'], $queryStr['author']);
      } else {
        $where = "";
      }
      //error_log("CLASSIFY MODEL: where " . $where);
      $result = $this->registry->db->select($table, $where, $bind);
      $model->entries = $result;
      return $model;
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      //error_log("NEW CLASSIFICATION");
      if ($queryStr != null && isset($queryStr['classification']) &&
        isset($queryStr['entry']) && isset($queryStr['username'])) {
        $where = "entry= ? AND username= ?";
        $bind = array($queryStr['entry'], $queryStr['username']);
        $result = $this->registry->db->select("classified_entries", $where, $bind);
        if (count($result) > 0) {
          // update
          $where = "entry= :entry AND username= :username";
          $bind = array("entry" => $queryStr['entry'],
            "username" => $queryStr['username']);
          if ($queryStr['classification'] == "") {
            //error_log("DELETE classification");
            $rowCount = $this->registry->db->delete("classified_entries", $where, $bind);
          } else {
            //error_log("UPDATE classification");
            $rowCount = $this->registry->db->update("classified_entries", array(
              "classification" => $queryStr['classification']), $where, $bind
            );
          }
          //error_log("UPDATE CLASSIFICATION ({$rowCount})");
        } else if ($queryStr['classification'] != "") {
          // insert
          $rowCount = $this->registry->db->insert("classified_entries", array(
            "id" => "DEFAULT(id)",
            "classification" => $queryStr['classification'],
            "entry" => $queryStr['entry'],
            "username" => $queryStr['username']
          ));
          //error_log("INSERT CLASSIFICATION ({$rowCount})");
        }
      } else {
        //error_log("NEW CLASSIFICATION: QS is NULL");
      }
    }
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
      $sql2 = "SELECT count(classified_entries.id) FROM classified_entries, " .
        "JournalEntries where JournalEntries.author= ? AND " .
        "classified_entries.entry=JournalEntries.id AND " .
        "classified_entries.username= ?";
      $bind = array($queryStr['student'], $queryStr['username']);
      $result2 = $this->registry->db->run($sql2, $bind);
      $done = $result2[0]['count(classified_entries.id)'];
      $model->total = $total;
      $model->done = $done;
      // see http://upload.wikimedia.org/wikipedia/commons/6/65/Harveyballs.v2.svg
    }
    return $model;
  }

  public function agree() {
    $this->model = new Registry();
    $sql = "SELECT entry, classification, author, username
        FROM journal.classified_entries, journal.JournalEntries 
where JournalEntries.journal = ? AND entry = JournalEntries.id
order by entry";
    $bind = array($_SESSION['currentJournal']);
    $result = $this->registry->db->run($sql, $bind);
    $this->model->result = $result;
    return $this->model;
  }

  public function agree1($queryStr) {
    $this->model = new Registry();
    if (isset($queryStr['lastdate'])) {
      $sql = "SELECT entry, classification, author, Students.sun, JournalEntries.dateTime
        FROM journal.classified_entries, journal.JournalEntries, journal.Students 
where JournalEntries.journal = ? AND entry = JournalEntries.id and 
JournalEntries.author = Students.username  and
JournalEntries.dateTime < ?
order by entry";
      $bind = array($_SESSION['currentJournal'], $queryStr['lastdate']);
      $result = $this->registry->db->run($sql, $bind);
      $this->model->result = $result;
    }
    return $this->model;
  }

}

?>

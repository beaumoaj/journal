<?php

/*
  Document   : indexModel
  Created on : 12-Sep-2011, 22:34:59
  Author     : David Bennett
 */

class tutorViewJournalModel extends BaseModel {

    private function truncate($str) {
        return (strlen($str) > 13) ? substr($str, 0, 10) . '...' : $str;
    }

    public function index() {
        $model = new Registry();
        $model->sidebarContent = '<h3>Journal Entries</h3>';
        $model->content = '';
        if (isset($_SESSION['username'])) {
            // logged in
            $model->sidebarContent .= '<p>logged in</p>';
            if (isset($_SESSION['selectedModule'])) {
                // not yet got list of modules
                $model->sidebarContent .= '<p>journals for ' . $_SESSION['selectedModule'] . '</p>';

                $where = "module= ?";
                $bind = array($_SESSION['selectedModule']);
                $model->sidebarContent .= '<p>' . $where . '</p>';
                $result = $this->registry->db->select('Journal', $where, $bind);
                // This user has some modules
                if (sizeof($result) == 1) {
                    foreach ($result as $row) {
                        $j_id = $row['id'];
                    }
                    $sql = "select distinct author from JournalEntries where journal= ?";
                    $bind = array($j_id);
                    $_SESSION['currentJournal'] = $j_id;
                    $result = $this->registry->db->run($sql, $bind);
                    $model->content .= "<h2>{$_SESSION['selectedModule']}: Students with Journal Entries</h2>";
                    if (!empty($result)) {
                        $model->content .= '<table class="journal">';
                        $model->content .= "<tr><th>SUN</th><th>User name</th><th>First name</th><th>Surname</th><th>Programme</th><th>Count</th></tr>";
                        foreach ($result as $row) {
                            $where = "Students.username= ? AND Students.username=User.username";
                            $bind = array($row['author']);
                            $fields = "Students.username, Students.sun, Students.gender, Students.programme, User.firstname, User.surname";
                            $sresult = $this->registry->db->select('Students, User', $where, $bind, $fields);
                            $where = "author= ?";
                            $bind = array($row['author']);
                            $fields = "COUNT('id')";
                            $cresult = $this->registry->db->select('JournalEntries', $where, $bind, $fields);
                            //error_log("Selecting author {$where} {$fields}");
                            foreach ($sresult as $student) {
                                $model->content .= "<tr onclick='document.location = \"" .
                                        __SITE_DIR . "/tutorViewJournal/viewJournal?username=" .
                                        $row['author'] . "\";'>";
                                $model->content .= "<td>" . $student['sun'] . "</td>";
                                $model->content .= "<td>" . $student['username'] . "</td>";
                                $model->content .= "<td>" . $student['firstname'] . "</td>";
                                $model->content .= "<td>" . $student['surname'] . "</td>";
                                $model->content .= "<td>" . $student['programme'] . "</td>";
                                $model->content .= "<td>" . $cresult[0]['COUNT(\'id\')'] . "</td>";
                                $model->content .= "</tr>";
                            }
                        }
                        $model->content .= "</table>";
                    } else {
                        $model->content .= "<p>You have no journal Entries</p>";
                    }
                } else {
                    $model->content .= "<p>No journal for this module</p>";
                }
            } else {
                $model->content .= "<p>Please select a module first</p>";
            }
        } else {
            $model->content .= "<p>Please login first</p>";
        }

        $model->title = 'Computer Science @ Aston: View Journal';
        return $model;
    }

    function generateEntry($idCode) {
        $sql = "select * from classifications order by value";
        $classResult = $this->registry->db->run($sql);

        $sid = "sel_{$idCode}";
        $did = "des_{$idCode}";
        $len = count($classResult);
        $content = ""; //<p>class result size is {$len}</p>";
        $content .= "<select id=\"{$sid}\" onchange=\"classify({$sid});\">";
        foreach ($classResult as $row) {
            $content .= "<option value='{$row['value']}'>{$row['name']}</option>";
        }
        $content .= "</select>";
        $content .= "<div id=\"{$did}\"></div>";
        $content .= "<script type=\"text/javascript\">wire({$sid}, {$did});</script>";
        return $content;
    }

    private function setUpData($classResult) {
        $scriptcontent = "<script type=\"text/javascript\">";
        $scriptcontent .= "var classifications = new Array();";
        foreach ($classResult as $row) {
            $scriptcontent .= "classifications['{$row['value']}'] = \"{$row['description']}\";";
        }
        $scriptcontent .= "</script>";
        return $scriptcontent;
    }

    public function view($queryStr) {
        $model = new Registry();
        $model->sidebarContent = '<h3>Journal Entries</h3>';
        $model->content = '';
        if ((isset($_SESSION['tutor']) || isset($_SESSION['ta'])) && isset($queryStr['username'])) {
            // logged in
            $model->sidebarContent .= "<p>{$_SESSION['username']} logged in looking for {$queryStr['username']}</p>";
            if (isset($_SESSION['selectedModule'])) {
                // not yet got list of modules
                $model->sidebarContent .= '<p>journals for ' . $_SESSION['selectedModule'] . '</p>';

                $where = "module= ?";
                $bind = array($_SESSION['selectedModule']);
                $model->sidebarContent .= '<p>' . $where . '</p>';
                $result = $this->registry->db->select('Journal', $where, $bind);
                // This user has some modules
                if (sizeof($result) == 1) {
                    foreach ($result as $row) {
                        $j_id = $row['id'];
                    }
                    $where = "journal= ? and author= ? ORDER BY dateTime DESC";
                    $bind = array($j_id, $queryStr['username']);
                    $_SESSION['currentJournal'] = $j_id;
                    //$model->content .= "<p>" . $where . "<p>";
                    $result = $this->registry->db->select('JournalEntries', $where, $bind);
                    $model->content .= "<h2>{$_SESSION['selectedModule']}: Journal Entries for {$queryStr['username']}</h2>";

                    if (!empty($result)) {
                        $model->content .= '<table class="journal">';
                        $model->content .= "<tr><th>Title</th><th>Description</th><th>Date</th></tr>";
                        foreach ($result as $row) {
                            $model->content .= "<tr onclick='document.location=\"" .
                                    __SITE_DIR . "/tutorViewJournal/viewEntry?entryId=" .
                                    $row['id'] . "\";'><td>" . $row['title'] . "</td>";
                            $model->content .= "<td>" . $row['description'] . "</td>";
                            $model->content .= "<td>" . $row['dateTime'] . "</td>";
                            $model->content .= "</tr>";
                        }
                        $model->content .= "</table>";
                    } else {
                        $model->content .= "<p>Student has no journal Entries {$where}</p>";
                    }
                } else {
                    $model->content .= "<p>No journal for this module</p>";
                }
            } else {
                $model->content .= "<p>Please select a module first</p>";
            }
        } else {
            $model->content .= "<p>Please login first</p>";
        }

        $model->title = 'Computer Science @ Aston: View Journal';
        return $model;
    }

    public function viewEntry($queryStr) {
        $model = new Registry();
        $model->sidebarContent = '<p>logged in</p>';
        $model->content = "";
        $model->title = "Edit Journal Entry";
        if ((isset($_SESSION['tutor']) || isset($_SESSION['ta']) ) && isset($queryStr['entryId'])) {
            $model->id = $queryStr['id'];
            // editing exiting entry
            $where = "id= ?";
            $bind = array($queryStr['entryId']);
            //error_log("tutor view entry where " . $where);
            $result = $this->registry->db->select('JournalEntries', $where, $bind);
            $model->dbRow = $result;
        } else {
            // creating new entry
            //error_log("tutor Cant view this istutor={$_SESSION['tutor']} entryid={$queryStr['entryId']}");
            $model->dbRow = null;
        }
        return $model;
    }

    public function getClassifications() {
        $model = new Registry();
        $result = $this->registry->db->select('classifications');
        $model->classifications = $result;
        return $model;
    }

}

?>

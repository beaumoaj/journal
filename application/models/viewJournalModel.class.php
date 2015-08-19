<?php

/*
  Document   : indexModel
  Created on : 12-Sep-2011, 22:34:59
  Author     : David Bennett
 */

class viewJournalModel extends BaseModel {

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

                $where = "module=?";
                $bind = array($_SESSION['selectedModule']);
                $model->sidebarContent .= '<p>' . $where . '</p>';
                $result = $this->registry->db->select('Journal', $where, $bind);
                // This user has some modules
                if (sizeof($result) == 1) {
                    foreach ($result as $row) {
                        $j_id = $row['id'];
                    }
                    $where = "journal=? and author=? ORDER BY dateTime DESC";
                    $bind = array($j_id, $_SESSION['username']);
                    $_SESSION['currentJournal'] = $j_id;
                    //$model->content .= "<p>" . $where . "<p>";
                    $result = $this->registry->db->select('JournalEntries', $where, $bind);
                    $model->content .= "<h3>{$_SESSION['selectedModule']}: Journal Entries for {$_SESSION['name']}</h3>";
                    $model->content .= "<p><a href='" . __SITE_DIR . "/viewJournal/newEntry'>Add a new entry</a></p>";
                    if (!empty($result)) {
                        $model->content .= '<table class="journal">';
                        $model->content .= "<tr><th>Title</th><th>Description</th><th>Date</th><th></th></tr>";
                        foreach ($result as $row) {
                            $model->content .= "<tr onclick='document.location = \"" .
                                    __SITE_DIR . "/viewJournal/viewEntry?entryId=" .
                                    $row['id'] . "\";'><td>" . $row['title'] . "</td>";
                            $model->content .= "<td>" . $row['description'] . "</td>";
                            $model->content .= "<td>" . $row['dateTime'] . "</td>";
                            $model->content .= "<td><a href='" . __SITE_DIR . "/viewJournal/editEntry?entryId=" . $row['id'] . "'>Edit</a></td></tr>";
                        }
                        $model->content .= "</table>";
                    } else {
                        $model->content .= "<p>You have no journal Entries</p>";
                    }
                    $model->content .= "<p><a href='" . __SITE_DIR . "/viewJournal/newEntry'>Add a new entry</a></p>";
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

    public function newScratch($queryStr) {
        // everyone who is logged in can do this
        //error_log("new scratch model");

        $model = new Registry();
        $model->sidebarContent = '<h3>Journal Entries</h3>';
        $model->title = 'Create new journal entry';
        $model->content = '';
        $where = "author=? and journal=?";
        $bind = array($queryStr['author'], $queryStr['journal']);
        $result = $this->registry->db->select('ScratchJournalEntries', $where, $bind);
        if (count($result) == 0) {
            //error_log("No scratch entry yet");
            if (isset($queryStr['journal']) && is_numeric($queryStr['journal']) &&
                    isset($queryStr['author']) &&
                    isset($queryStr['dateTime'])
            ) {
                //error_log("INSERT new scratch entry");
                // create a new entry
                $rowCount = $this->registry->db->insert("ScratchJournalEntries", array(
                    "journal" => $queryStr['journal'],
                    "title" => "Entry {$queryStr['dateTime']}",
                    "author" => $queryStr['author'],
                    "dateTime" => $queryStr['dateTime']
                ));
                //error_log("SELECT new scratch entry");
                $result = $this->registry->db->select('ScratchJournalEntries', $where, $bind);
            }
        }
        //error_log("SETTING DB ROW " . count($result));
        $model->dbRow = $result;
        return $model;
    }

    public function updateScratch($queryStr) {
        //error_log("UPDATE SCRATCH");
        if (isset($queryStr['journal']) && is_numeric($queryStr['journal']) &&
                isset($queryStr['author']) &&
                isset($queryStr['dateTime'])
        ) {
            $sections = array('title', 'description', 'reflection', 'concepts', 'whatNext', 'referenceList', 'notes');
            $info = array();
            $info['dateTime'] = $queryStr['dateTime'];
            foreach ($sections as $section) {
                //error_log("looking for {$section}");
                if (isset($queryStr[$section])) {
                    $info[$section] = urldecode($queryStr[$section]);
                    //error_log("UPDATE SCRATCH: {$section} {$info[$section]}");
                }
            }
            if (isset($queryStr['id'])) {
                $table = "JournalEntries";
                $where = "id= :id AND journal= :journal AND author= :author";
                $bind = array("id" => $queryStr['id'], "journal" => $queryStr['journal'], "author" => $queryStr['author']);
            } else {
                $table = "ScratchJournalEntries";
                $where = "journal= :journal AND author= :author";
                $bind = array("journal" => $queryStr['journal'], "author" => $queryStr['author']);
            }
            $rowCount = $this->registry->db->update($table, $info, $where, $bind);
        }
    }

    public function copyEntry($queryStr) {
        //error_log("COPY SCRATCH");
        if (isset($queryStr['journal']) && is_numeric($queryStr['journal']) &&
                isset($queryStr['author']) &&
                isset($queryStr['dateTime'])
        ) {
            $where = "author=? and journal=?";
            $bind = array($queryStr['author'], $queryStr['journal']);
            $result = $this->registry->db->select('ScratchJournalEntries', $where, $bind);
            if (count($result) == 1) {
                $row = $result[0];
                $sections = array('title', 'description', 'reflection', 'concepts', 'whatNext', 'referenceList', 'notes');
                $info = array();
                $info['dateTime'] = $queryStr['dateTime'];
                $info['author'] = $queryStr['author'];
                $info['journal'] = $queryStr['journal'];
                $info['id'] = 'default(id)';
                foreach ($sections as $section) {
                    //error_log("looking for {$section}");
                    if (isset($row[$section])) {
                        $info[$section] = $row[$section];
                        //error_log("UPDATE SCRATCH: {$section} {$info[$section]}");
                    }
                }
                $rowCount = $this->registry->db->insert("JournalEntries", $info);
                //error_log("INSERTED {$rowCount} rows");
                $rowCount = $this->registry->db->delete("ScratchJournalEntries", $where, $bind);
                //error_log("DELETED {$rowCount} rows");
            }
        }
    }

    public function edit($queryStr) {
        $model = new Registry();
        $model->sidebarContent = '<p>logged in</p>';
        $model->content = "";
        $model->title = "Edit Journal Entry";
        if (isset($queryStr['id'])) {
            $model->id = $queryStr['id'];
            //error_log("setting model id to {$model->id}");
            // editing exiting entry
            $where = "id=? and author=?";
            $bind = array($queryStr['id'], $_SESSION['username']);
            //error_log("edit entry where " . $where);
            $result = $this->registry->db->select('JournalEntries', $where, $bind);
            $model->dbRow = $result;
        } else {
            // creating new entry
            //error_log("Create new Entry");
            $model->dbRow = null;
        }
        return $model;
    }

}

?>

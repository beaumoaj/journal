<?php

/*
  Document   : indexModel
  Created on : 12-Sep-2011, 22:34:59
  Author     : David Bennett
 */

class journalAdminModel extends BaseModel {

    public function index() {
        $model = new Registry();
        $model->sidebarContent = '';
        $model->content = '<p>Unset</p>';
        if (isset($_SESSION['username']) && isset($_SESSION['tutor'])) {
            // logged in
            $model->sidebarContent .= '<p>logged in</p>';
            if (!isset($_SESSION['modules'])) {
                // not yet got list of modules
                $model->sidebarContent .= '<p>No Modules Yet</p>';

                $where = "owner= ?";
                $bind = array($_SESSION['username']);
                $model->sidebarContent .= '<p>' . $where . '</p>';
                $result = $this->registry->db->select('Module', $where, $bind);
                // This user has some modules
                if (sizeof($result) > 0) {
                    $model->sidebarContent .= '<p>Setting ' . sizeof($result) . ' Modules</p>';
                    $model->content = $this->getModules($result);
                } else {
                    $model->content .= "<p>No modules " . sizeof($result) . "</p>";
                }
            }
        }

        $model->title = 'Computer Science @ Aston: Journal Administration';
        return $model;
    }

    private function getModules($modules) {
        $moduleText = '<tr><th>Module Code</th><th>Module Title</th><th>Action</th>';
        foreach ($modules as $module) {
            $moduleText .= '<tr>';
            $moduleText .= '<td>' . $module['code'] . '</td>';
            $moduleText .= '<td>' . $module['title'] . '</td>';
            $where = "module= ?";
            $bind = array($module['code']);
            $result = $this->registry->db->select('Journal', $where, $bind);
            if (sizeof($result) > 0) {
                $moduleText .= '<td><a href="' . __SITE_DIR . '/journalAdmin/manage?code=' . $module['code'] . '">Manage</a></td>';
            } else {
                $moduleText .= '<td><a href="' . __SITE_DIR . '/journalAdmin/create?code=' . $module['code'] . '">Create</a></td>';
            }
            $moduleText .= '</tr>';
        }
        return $moduleText;
    }

    public function create() {
        $model = new Registry();
        $model->code = addslashes ($_GET['code']);
        $model->title = 'Computer Science @ Aston: Create Journal for ' . $model->code;
        $where = "owner= ? and code= ?";
        $bind = array($_SESSION['username'], $model->code);
        $result = $this->registry->db->select('Module', $where, $bind);
        $model->canCreate = sizeof($result);
        return $model;
    }

    public function newJournal($queryStr) {
        if (isset($queryStr['code']) && isset($queryStr['title']) &&
                isset($queryStr['description']) && isset($queryStr['owner'])) {
            $this->registry->db->BeginTransaction();
            $rowCount = $this->registry->db->insert("Journal", array(
                "module" => $queryStr['code'],
                "title" => $queryStr['title'],
                "description" => $queryStr['description'],
                "owner" => $queryStr['owner']
                    )
            );
            //error_log("Inserted jounal updated rows=".$rowCount);

            $j_id = $this->registry->db->lastInsertId();
            //error_log("Inserted jounal id=".$j_id);
            $rowCount += $this->registry->db->insert("TutorToJournal", array(
                "journal" => $j_id,
                "username" => $queryStr['owner']
                    )
            );
            if ($rowCount == 2) {
                //error_log("updated 2 rows, committing");
                $this->registry->db->commit();
            } else {
                //error_log("only updated ".$rowCount." rows");
                $this->registry->db->rollBack();
            }
        } 
    }

    public function manage() {
        $model = new Registry();
        return $model;
    }

}

?>

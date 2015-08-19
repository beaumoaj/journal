<?php

/*
  Document   : indexModel
  Created on : 12-Sep-2011, 22:34:59
  Author     : David Bennett
 */

class selectModuleModel extends BaseModel {

    public function index() {
        $model = new Registry();
        $model->sidebarContent = '';
        $model->content = '<p>Unset</p>';
        if (isset($_SESSION['username'])) {
            // logged in
            $model->sidebarContent .= '<p>logged in</p>';
            if (!isset($_SESSION['modules'])) {
                // not yet got list of modules
                $model->sidebarContent .= '<p>No Modules Yet</p>';

                //$where = "username='{$_SESSION['username']}'";
                //error_log("selectModuleModel " . $where);
                //$model->sidebarContent .= '<p>' . $where . '</p>';
                if (!isset($_SESSION['tutor']) && !isset($_SESSION['ta'])) {
                    $stmt = $this->registry->db->prepare(
                            "select UserToModule.code from UserToModule, Journal where UserToModule.username= :user AND UserToModule.code=Journal.module");
                    $stmt->bindParam(':user', $_SESSION['username'], PDO::PARAM_STR, 10);
                    $type = 'student';
                } else if (isset($_SESSION['tutor'])) {
                    // user is a tutor let them select their own journals
                    $stmt = $this->registry->db->prepare(
                            "select module from Journal where owner= :user");
                    $stmt->bindParam(':user', $_SESSION['username'], PDO::PARAM_STR, 10);
                    $type = 'tutor';
                } else if (isset($_SESSION['ta'])) {
                    // user is a TA let them select their own journals
                    $stmt = $this->registry->db->prepare(
                            "select moduleCode from TeachAssist where username= :user");
                    $stmt->bindParam(':user', $_SESSION['username'], PDO::PARAM_STR, 10);
                    $type = 'ta';
                }
                $stmt->execute();
                $result = $stmt->fetchAll();
                $result2 = null;
                // This user has some modules
                if (sizeof($result) > 0  || isset($_SESSION['tutor'])) {
                    $model->sidebarContent .= '<p>Setting ' . sizeof($result) . ' Modules</p>';
                    //error_log("selectModuleModel found modules: " . sizeof($result));
                    $model->content = '<tr><th>Module Code</th><th>Module Title</th><th>Action</th>';
                    $model->content .= $this->getModules($result, $type);
                    if (isset($_SESSION['tutor'])) {
                        $stmt2 = $this->registry->db->prepare(
                                "select moduleCode from TeachAssist where username= :user2");
                        $stmt2->bindParam(':user2', $_SESSION['username'], PDO::PARAM_STR, 10);
                        $stmt2->execute();
                        $result2 = $stmt2->fetchAll();
                        //error_log("result2 has " . count($result2) . " entries");
                        $model->content .= $this->getModules($result2, 'ta');
                    }

                } else {
                    $model->content .= "<p>No modules " . sizeof($result) . "</p>";
                    //error_log("selectModuleModel found NO modules");
                }
            }
        }

        $model->title = 'Computer Science @ Aston: Select Journal';
        return $model;
    }

    private function getModules($modules, $type) {

        $moduleText = '';
        foreach ($modules as $module) {
            if ($type == 'student') {
                $code = $module['code'];
            } else if ($type == 'tutor') {
                $code = $module['module'];
            } else if ($type == 'ta') {
                $code = $module['moduleCode'];
            }
            //error_log("found module {$code}");
            /*
              if (!isset($_SESSION['tutor']) && !isset($_SESSION['ta'])) {
              $code = $module['code'];
              } else if (isset($_SESSION['tutor'])) {
              $code = $module['module'];
              } else if (isset($_SESSION['ta'])) {
              $code = $module['moduleCode'];
              }
             */
            $where = "code= ?";
            $bind = array($code);
            $result = $this->registry->db->select('Module', $where, $bind);
            foreach ($result as $row) {
                $moduleText .= '<tr>';
                $moduleText .= '<td>' . $code . '</td>';
                $moduleText .= '<td>' . $row['title'] . '</td>';
                $moduleText .= '<td><a href="' . __SITE_DIR . '/selectModule/selected?code=' . $code . '">Select</a></td>';
                $moduleText .= '</tr>';
            }
        }
        return $moduleText;
    }

    public function selected($queryStr) {
        if (isset($queryStr['code']) && isset($_SESSION['username'])) {
            //error_log("select Module selecting " . $queryStr['code']);
            $_SESSION['selectedModule'] = $queryStr['code'];
            $where = "module= ?";
            $bind = array($_SESSION['selectedModule']);
            $result = $this->registry->db->select('Journal', $where, $bind);
            if (sizeof($result) == 1) {
                $_SESSION['currentJournal'] = $result[0]['id'];
            }
        } else {
            //error_log("select Module CANT SELECT ");
        }
    }

}

?>

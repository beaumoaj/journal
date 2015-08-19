<?php

/*
  Document   : indexModel
  Created on : 12-Sep-2011, 22:34:59
  Author     : David Bennett
 */

class userAdminModel extends BaseModel {

    public function index() {
        $model = new Registry();
        $model->sidebarContent = '';
        $model->content = '<p>Unset</p>';
        if (isset($_SESSION['username']) && isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
            // logged in
            $model->sidebarContent .= '<p>logged in as administrator</p>';
            $model->content = "<p>Content</p>";
        }

        $model->title = 'ADMIN Home Page: Computer Science @ Aston: Journal Administration';
        return $model;
    }

    public function addUser($queryStr) {
        if ($_SESSION['admin'] == true && isset($queryStr['username']) && isset($queryStr['firstname']) &&
                isset($queryStr['surname'])) {
            $this->registry->db->BeginTransaction();
            $rowCount = $this->registry->db->insert("User", array(
                "username" => $queryStr['username'],
                "firstname" => $queryStr['firstname'],
                "surname" => $queryStr['surname'],
                "password" => 'abcd'
                    )
            );
            //error_log("Inserted user updated rows=" . $rowCount);

            if (isset($queryStr['isTutor'])) {

                $rowCount += $this->registry->db->insert("Tutors", array(
                    "username" => $queryStr['username']
                        )
                );
                if ($rowCount == 2) {
                    //error_log("{$queryStr['username']} is a tutor, committing");
                    $this->registry->db->commit();
                } else {
                    //error_log("only updated " . $rowCount . " rows");
                    $this->registry->db->rollBack();
                }
            } else {
                $this->registry->db->commit();
            }
        }
    }

    public function addTA($queryStr) {
        if ($_SESSION['admin'] == true && isset($queryStr['username']) &&
                isset($queryStr['moduleCode'])) {
            $rowCount = $this->registry->db->insert("TeachAssist", array(
                "username" => $queryStr['username'],
                "moduleCode" => $queryStr['moduleCode']
                    )
            );
            //error_log("Inserted user updated rows=" . $rowCount);
        }
    }

    public function getUsers() {
        if ($_SESSION['admin'] == true) {
            $fields = "username, firstname, surname";
            return $this->registry->db->select('User', "", "", $fields);
        }
    }

    public function getModules() {
        if ($_SESSION['admin'] == true) {
            $fields = "title, code";
            return $this->registry->db->select('Module', "", "", $fields);
        }
    }

    public function getTutors() {
        if ($_SESSION['admin'] == true) {
            $where = "User.username=Tutors.username";
            $fields = "User.username, User.firstname, User.surname";
            return $this->registry->db->select('User, Tutors', $where, "", $fields);
        }
    }

    public function addModule($queryStr) {
        if ($_SESSION['admin'] == true && isset($queryStr['code']) && isset($queryStr['title']) &&
                isset($queryStr['owner'])) {
            $rowCount = $this->registry->db->insert("Module", array(
                "title" => $queryStr['title'],
                "owner" => $queryStr['owner'],
                "code" => $queryStr['code']
                    )
            );
            //error_log("Inserted {$queryStr['code']} updated rows=" . $rowCount);
        }
    }

    public function studentUpload($queryStr) {
        if ($_SESSION['admin'] == true && isset($queryStr['code'])) {
            $moduleCode = $queryStr['code'];
            if ($_FILES["csv"]["error"] > 0) {
                error_log("Error: " . $_FILES["csv"]["error"]);
            } else {
                //error_log("Upload: " . $_FILES["csv"]["name"]);
                //error_log("Type: " . $_FILES["csv"]["type"]);
                //error_log("Size: " . ($_FILES["csv"]["size"] / 1024));
                //error_log("Stored in: " . $_FILES["csv"]["tmp_name"]);
                $fh = fopen($_FILES["csv"]["tmp_name"], 'r');
                if ($fh != FALSE && $_FILES["csv"]["type"] == "text/csv") {
                    //error_log("OPEN FILE: " . $_FILES["csv"]["tmp_name"]);
                    while (($student = fgetcsv($fh, 8192)) !== FALSE) {
                        $sun = $student[0];
                        $surname = $student[1];
                        $firstname = $student[2];
                        $gender = $student[3];
                        $homeOverseas = $student[4];
                        $username = substr($student[5], 0, strpos($student[5], '@'));
                        $stage = $student[6];
                        $programme = $student[7];
                        $attempt = $student[8];
                        $group = $student[9];
                        // $subgroup = $student[10];
                        $danu = $student[11];
                        //error_log("PROCESS STUDENT: {$username}");

                        $errorMessage = "";
                        $this->registry->db->BeginTransaction();

                        // select from User where username = '{$username}'
                        $where = "username=?";
                        $bind = array($username);
                        $result = $this->registry->db->select('User', $where, $bind);
                        // if not a user then insert into user, 
                        if (count($result) == 0) {
                            //error_log("Insert {$username} who is {$firstname} ${surname}");
                            $rowCount = $this->registry->db->insert("User", array(
                                "username" => $username,
                                "firstname" => $firstname,
                                "surname" => $surname,
                                "password" => 'abcd'
                                    )
                            );
                            if ($rowCount != 1) {
                                $errorMessage .= "Problem Inserting {$username} who is {$firstname} ${surname}";
                            }
                        }
                        // 
                        // start transaction, 
                        $where = "username=?";
                        $bind = array($username);
                        $result = $this->registry->db->select('Students', $where, $bind);
                        // if not a user then insert into user, 
                        if (count($result) == 0) {

                            // insert into students, 
                            $rowCount = $this->registry->db->insert("Students", array(
                                "username" => $username,
                                "sun" => $sun,
                                "gender" => $gender,
                                "homeOverseas" => $homeOverseas,
                                "stage" => $stage,
                                "programme" => $programme,
                                "attempt" => $attempt,
                                "tutorialGroup" => $group,
                                "danu" => $danu,
                                    )
                            );
                            if ($rowCount != 1) {
                                $errorMessage .= "Could not insert student {$username}";
                            }
                        }
                        // insert into user to module table
                        $where = "username=? and code=?";
                        $bind = array($username, $moduleCode);
                        $result = $this->registry->db->select('UserToModule', $where, $bind);
                        if (count($result) == 0) {
                            $rowCount = $this->registry->db->insert("UserToModule", array(
                                "username" => $username,
                                "code" => $moduleCode
                                    )
                            );
                            if ($rowCount != 1) {
                                $errorMessage .= "Problem linking {$username} to {$moduleCode}";
                            }
                        }
                        // end transaction
                        if ($errorMessage == "") {
                            $this->registry->db->commit();
                        } else {
                            $this->registry->db->rollBack();
                        }
                    }
                    fclose($fh);
                } else {
                    error_log("Could not open CSV file");
                }
            }
        }
    }

}

?>

<?php

/*
  Document   : userModel
  Created on : 14-Sep-2011, 00:32:59
  Author     : David Bennett
 */

//require_once 'Mail.php';
require_once 'Net/LDAP2.php';

class userModel extends BaseModel {

    public function index() {
        
    }

    public function login($queryStr) {
        // If username and password provided
        if (isset($queryStr['username']) && isset($queryStr['password'])) {
            $username = addslashes($queryStr['username']);
            $password = addslashes($queryStr['password']);
            // If not already logged in
            if (!isset($_SESSION['username'])) {

                $_SESSION['start'] = "login " . $queryStr['username']. " ";
                $netLogin = false;
                if ($this->registry->ldapAuth == true) {
                    $where = "username=?";
                    $bind = array($username);
                    $result = $this->registry->db->select('User', $where, $bind);
                    // LDAP Authentication
                    $config = array(
                        'binddn' => $queryStr['username'] . "@aston.ac.uk",
                        'bindpw' => $queryStr['password'],
                        'basedn' => 'dc=campus,dc=aston,dc=ac,dc=uk',
                        'host' => 'gc.campus.aston.ac.uk',
                        'port' => '3268'
                    );

                    // Connecting using the configuration:
                    $ldap = Net_LDAP2::connect($config);
                    if ($this->registry->ldapAuth == true && Net_LDAP2::isError($ldap)) {
                        error_log("ldap ERROR=" . $ldap->getMessage());
                    } else {
                        //error_log("LDAP CONNECTED");
                        $netLogin = TRUE;
                    }
                } else {
                    $where = "username=? and password=?";
                    $bind = array($username, $password);
                    $result = $this->registry->db->select('User', $where, $bind);
                    $netLogin = true;
                }

                // If user/pass match a user then set login session
                if ($netLogin == TRUE && sizeof($result) == 1) {
			if(!isset($_SESSION["timeout"])){
			     $_SESSION['timeout'] = time();
			}
			$st = $_SESSION['timeout'] + 3600; //session time is 1 hour

                    $_SESSION['start'] .= "One row ";
                    $row = $result[0];
                    $_SESSION['start'] .= sizeof($row) . " ";
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['name'] = $row['firstname'] . ' ' . $row['surname'];
                    $where = "username=?";
                    $bind = array($username);
                    $result = $this->registry->db->select('Admin', $where, $bind);
                    if (sizeof($result) == 1) {
                        $row = $result[0];
                        $_SESSION['admin'] = true;
                    }
                    $result = $this->registry->db->select('Tutors', $where, $bind);
                    if (sizeof($result) == 1) {
                        $row = $result[0];
                        $_SESSION['tutor'] = true;
                    }
                    $result = $this->registry->db->select('TeachAssist', $where, $bind);
                    if (sizeof($result) >= 1) {
                        $row = $result[0];
                        $_SESSION['ta'] = true;
                    }
                } else {
                    $_SESSION['start'] .= "no rows";
                }
            }
        }

        // If login was successful
        if (isset($_SESSION['username']))
            $_SESSION['invalid_login'] = false;
        else
            $_SESSION['invalid_login'] = true;
    }

    public function logout() {
        session_destroy();
    }

    public function deleteUser($username) {
        $this->registry->db->connect();

        // Set activated flag
        $res = $this->registry->db->delete("users","WHERE username=?", array($username));

        if (!$res->succeeded())
            return "There was an error updating the database";

        $this->registry->db->close();
    }

}

?>

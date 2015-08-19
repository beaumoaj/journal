<?php
/*
    Document   : init
    Created on : 12-Sep-2011, 20:29:39
    Author     : David Bennett
*/

// Auto load classes when 'new' is used
function __autoload ( $class_name )
{
    // Get the filename of the class
    $filename = $class_name . '.class.php';

    // Path to the class
    $file = __SITE_PATH . '/includes/' . $filename;

    // Check the file exists
    if ( file_exists ( $file ) == false )
        return false;

    // Include the class file
    include $file;
}


// Roles
define ( 'ADMIN', 1 );
define ( 'TUTOR', 2 );

// Create the registry
$registry = new Registry();
// Config
//$registry->ldapAuth = true;
$registry->ldapAuth = false;
$registry->dbHost = 'localhost';
$registry->dbName = 'journal';
$registry->dbUser = 'root';
$registry->dbPass = 'ngo.set';
$registry->dsn = 'mysql:host='.$registry->dbHost.';dbname='.$registry->dbName;
$registry->admin = 'a.j.beaumont@aston.ac.uk';
// User profile
$registry->username = isset ( $_SESSION['username'] ) ? $_SESSION['username'] : null;
$registry->name = isset ( $_SESSION['name'] ) ? $_SESSION['name'] : null;
$registry->roles = isset ( $_SESSION['roles'] ) ? $_SESSION['roles'] : null;
$registry->myEvents = isset ( $_SESSION['myEvents'] ) ? $_SESSION['myEvents'] : null;

// Create database connection
$registry->db = new db( $registry->dsn, $registry->dbUser = 'root', $registry->dbPass = 'ngo.set' );
$registry->db->setErrorCallbackFunction('print');

// Add router to registry
$registry->router = new Router ( $registry );

// Load the template
$registry->template = new Template ( $registry );

?>

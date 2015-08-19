<?php
/*
    Document   : index
    Created on : 12-Sep-2011, 20:24:10
    Author     : David Bennett
*/

// Error reporting
error_reporting ( E_ALL );

// Constant site path
$site_path = realpath ( dirname ( __FILE__ ) );
define ( '__SITE_PATH', $site_path );

// Directory of site if not running on root
$url = dirname ( $_SERVER['PHP_SELF'] );
// define ( '__SITE_DIR', $url );
define ( '__SITE_DIR', '' );

// Paths
define ( '__MODEL_PATH', __SITE_PATH . '/application/models' );
define ( '__VIEW_PATH', __SITE_PATH . '/application/views' );
define ( '__CONTROLLER_PATH', __SITE_PATH . '/application/controllers' );

// Start a session
session_start();

// Initialization
include __SITE_PATH . '/includes/init.php';

// Run the controller
$registry->router->run();
?>

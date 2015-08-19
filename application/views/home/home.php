<?php
/*
  Document   : home
  Created on : 12-Sep-2011, 22:52:57
  Author     : David Bennett
 */
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <?php include __VIEW_PATH . '/common/head.php' ?>
    </head>

    <body>

        <?php include __VIEW_PATH . '/common/header.php' ?>

        <div id="side_bar">
            <?php include __VIEW_PATH . '/sidebar/userMenu.php' ?>
        </div>

        <div id="main_content">
            <?php
            $breadcrumbs->draw();
            if (isset($_SESSION['invalid_login']) && $_SESSION['invalid_login']) {
                echo '<h2>' . $_SESSION['start'] . 'Invalid username/password, you are not logged in.  Please enter a valid username and password.</h2>';
                $_SESSION['invalid_login'] = false;
            }
            ?>

            <h1><?php echo $model->title; ?></h1>

            <?php
            if (!isset($_SESSION['username'])) {
                ?>
                <p>Please login first.</p>
                <?php
            }
            ?>

            <p>Please select a module first from the menu at the top of the screen.  Then edit your journal.</p>

        </div>

        <?php include __VIEW_PATH . '/common/footer.php' ?>

    </body>

</html>
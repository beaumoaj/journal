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
        <?php include __VIEW_PATH . '/common/dojoHead.php' ?>

        <div id="side_bar">
            <?php include __VIEW_PATH . '/sidebar/userMenu.php' ?>
        </div>
        <script type="text/javascript">
            require([
                "dojo/store/JsonRest", "dijit/form/FilteringSelect", "dojo/domReady!"
            ], function (JsonRest, FilteringSelect) {
                var tutorStore = new JsonRest({
                    target: "/userAdmin/getUsers",
                    idProperty: "username"
                });
                var moduleStore = new JsonRest({
                    target: "/userAdmin/getModules",
                    idProperty: "code"
                });
                var tutorSelect = new FilteringSelect({
                    id: "userSelect",
                    name: "user",
                    store: tutorStore,
                    searchAttr: "username"
                }, "userSelect").startup();
                var moduleSelect = new FilteringSelect({
                    id: "moduleSelect",
                    name: "module",
                    store: moduleStore,
                    searchAttr: "code"
                }, "moduleSelect").startup();
            });
        </script>

        <div id="main_content" class="claro">
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
            if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
                ?>
                <p>You are an Administrator.</p>
                <?php
            }
            ?>

            <h2>Set up Teaching Assistant</h2>
            <form action="<?php echo __SITE_DIR . '/userAdmin/addTA' ?>" 
                  method="POST" name="taUpload">
                User:     <input id="userSelect" name="user"/><br/>
                Module Code: <input id="moduleSelect" name="code"/><!-- input type="text" name="code"/ --><br/>
                <input type="submit" value="Add Teaching Assistant"/>
            </form>

        </div>

        <?php include __VIEW_PATH . '/common/footer.php' ?>

    </body>

</html>
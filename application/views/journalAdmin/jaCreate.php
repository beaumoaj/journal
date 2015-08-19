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
            <?php echo $model->sidebarContent; ?>
        </div>

        <div id="main_content">
            <?php
            $breadcrumbs->draw();
            ?>

            <h1><?php echo $model->title; ?></h1>

            <?php
            if (!isset($_SESSION['username'])) {
                ?>
                <p>Please login first.</p>
                <?php
            } else {
                if ($this->model->canCreate) {
                    echo '<p>Creating journal for ' . $this->model->code . '</p>';
                } else {
                    echo '<p>Cannot create journal for ' . $this->model->code . '.  Only the module owner can create a journal.</p>';
                }
            }
            ?>
            <form action="<?php echo __SITE_DIR . '/journalAdmin/createJournal' ?>" method="POST">
                <input type="hidden" name="code" value="<?php echo $this->model->code ?>" />
                Title: <input type="text" name="title" /><br/>
                Description: <input type="textarea" name="description" /><br/>
                <input type="submit" value="Create"/>
            </form>

        </div>

        <?php include __VIEW_PATH . '/common/footer.php' ?>

    </body>

</html>
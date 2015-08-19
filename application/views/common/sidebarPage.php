<?php
/*
    Document   : sidebarPage
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
        <?php $breadcrumbs->draw() ?>

        <h1><?php echo $model->title; ?></h1>

        <?php echo $model->content; ?>
    </div>

    <?php include __VIEW_PATH . '/common/footer.php' ?>

</body>

</html>
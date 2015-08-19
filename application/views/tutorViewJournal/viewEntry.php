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

    <body class="claro">

        <?php include __VIEW_PATH . '/common/header.php' ?>

        <div id="side_bar">
            <?php echo $model->sidebarContent; ?>
        </div>

        <div id="main_content">
            <?php
            $breadcrumbs->draw();
            ?>

            <h1>View Journal Entry</h1>

            <?php
            if (!isset($_SESSION['username'])) {
                ?>
                <p>Please login first.</p>
                <?php
            } else {
                echo $model->content;

                if ($model->dbRow != null) {
                    $data = $model->dbRow[0];
                    echo "<h2>({$_SESSION['selectedModule']}) Entry written by {$data['author']} on {$data['dateTime']}</h2>";

                    ?>
                        <b>Title:</b> <br/>
                        <div id="title" class="viewable"><?php echo $data['title']; ?></div>
                        <b>Description:</b> <br/>
                        <div id="description" class="viewable"><?php echo $data['description']; ?></div>
                        <b>Reflection:</b><br/>
                        <div id="reflection" class="viewable"><?php echo $data['reflection']; ?></div>
                        <b>Concepts:</b> <br/>
                        <div id="concepts" class="viewable"><?php echo $data['concepts']; ?></div>
                        <b>What Next:</b> <br/>
                        <div id="whatNext" class="viewable"><?php echo $data['whatNext']; ?></div>
                        <b>References:</b> <br/>
                        <div id="referenceList" class="viewable"><?php echo $data['referenceList']; ?></div>
                        <b>Notes:</b> <br/>
                        <div id="notes" class="viewable"><?php echo $data['notes']; ?></div>
                    <?php
                } else {
                    echo "<h3>Error: Couldn't find that journal entry</h3>";
                    ?>
                    <?php
                }
            }
            ?>
        </div>
        <?php include __VIEW_PATH . '/common/footer.php' ?>

    </body>

</html>

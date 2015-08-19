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
    <?php include __VIEW_PATH . '/common/dojoHead.php' ?>
    <!--
    <script type="text/javascript" src="/script/tinymce/tinymce.min.js">
        //
    </script>
    -->
    <script type="text/javascript" src="/script/journal_1.js">
      //
    </script>
    <script type="text/javascript">
//<!--
// check that they entered an amount tested, an amount passed,
// and that they didn't pass units than they more than tested
      var finishing = false;
      window.onbeforeunload = function () {
        //alert("dirty is " + dirty);
        var hasId = dojo.byId('entryId');
        alert("hasId is " + hasId + " finishing is " + finishing);
        return (((hasId === null) && (finishing == false)) ?
          "If you leave this page, your new entry will be saved for you.\nWhen you next choose to create a new entry you will return to this page.\nTo save your edits and create the new journal entry, click the Finish button at the bottom of the page." :
          null);
      }

      function checkEntry()
      {
        //alert("check entry");

        var title = dojo.byId("title").innerHTML;
        var description = dojo.byId("description").innerHTML;
        var errorMessage = "";
        if (title == null || title == "") {
          errorMessage += "You must enter a title\n";
        }
        if (description == null || description == "") {
          errorMessage += "You must enter a description\n";
        }
        if (errorMessage.length == 0) {
          dirty = false;
          finishing = true;
          return true;
        } else {
          alert(errorMessage);
          return false;
        }
      }
// -->

    </script>
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

      <h1><?php echo $model->title; ?></h1>

      <?php
      if (!isset($_SESSION['username'])) {
        ?>
        <p>Please login first.</p>
        <?php
      } else {
        echo $model->content;
        if (count($model->dbRow) == 1) {
          echo '<h3>Edit journal entry</h3>';
          $data = $model->dbRow[0];
          $setId = $model->__exists('id');
          ?>
          <form action = "<?php echo __SITE_DIR . '/viewJournal/commitEntry' ?>" method = "POST"
                name = "entryForm" onsubmit = "return checkEntry();">
                  <?php
                  if ($setId) {
                    $valueId = $model->id;
                    echo '<p><a href = "/viewJournal">Return to Journal entries</a></p>';
                    echo '<p>Edits are automatically saved.</p>';
                    echo "<input type='hidden' name='id' value='{$valueId}' id='entryId'/>";
                  } else {
                    // $valueId = "ERROR";
                    echo '<p>Edits are automatically saved but you need to click the Finish button to accept your edits.</p>';
                  }
                  ?>
            <div class="journal_entry">
              <b>Title:</b> <br/>
              <div id="title" class="editable" onclick="edit('title');"><?php echo $data['title']; ?></div>
              <b>Description:</b><br/>
              <div id="description" class="editable" onclick="edit('description');"><?php echo $data['description']; ?></div>
              <b>Reflection:</b><br/>
              <div id="reflection" class="editable" onclick="edit('reflection');"><?php echo $data['reflection']; ?></div>
              <b>Concepts:</b><br/>
              <div id="concepts" class="editable" onclick="edit('concepts');"><?php echo $data['concepts']; ?></div>
              <b>What Next:</b><br/>
              <div id="whatNext" class="editable" onclick="edit('whatNext');"><?php echo $data['whatNext']; ?></div>
              <b>References:</b><br/>
              <div id="referenceList" class="editable" onclick="edit('referenceList');"><?php echo $data['referenceList']; ?></div>
              <b>Notes:</b><br/>
              <div id="notes" class="editable" onclick="edit('notes');"><?php echo $data['notes']; ?></div>
              <?php
              if (!$setId) {
                echo "<input type = 'submit' value = 'Finish'/>";
              } else {
                echo '<p><a href = "/viewJournal">Return to Journal entries</a></p>';
              }
              ?>
            </div>
          </form>
          <?php
        } else {
          echo "<h1>No scratch entry</h1>";
        }
      }
      ?>
    </div>

    <?php include __VIEW_PATH . '/common/footer.php' ?>

  </body>

</html>

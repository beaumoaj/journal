<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<html  xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <?php include __VIEW_PATH . '/common/head.php' ?>
    <?php include __VIEW_PATH . '/common/dojoHead.php' ?>


    <script>
      require(["dojo/parser", "dijit/form/DateTextBox"]);
    </script>
  </head>
  <body class="claro">
    <?php include __VIEW_PATH . '/common/header.php' ?>
    <h1>Agreement data download</h1>
    <p>Select a date being the first day to be OMITTED from the data and click submit</p>
    <form method="POST" action="/classify/agreed1">
      <label for="date1">Drop down Date box:</label>
      <?php
      $pattern = "Y-m-d";
      $dateToday = date($pattern);
      ?>
      <input type="text" name="lastdate" id="date1" value="<?php echo $dateToday?>"
             data-dojo-type="dijit/form/DateTextBox"
             required="true" />
      <input type="submit" value="Submit"/>
    </form>
  </body>
</html>
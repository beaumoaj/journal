<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <?php include __VIEW_PATH . '/common/head.php' ?>
    <?php include __VIEW_PATH . '/common/dojoHead.php' ?>
    <title>View Journals</title>
  </head>

  <body class="claro">

    <?php include __VIEW_PATH . '/common/header.php' ?>

    <div id="side_bar">

    </div>
    <div id="main_content">
      <div class="classify_panel">
        <table class="journal">

          <thead>
            <tr><th>Student</th>
              <th>Entry ID</th>
              <th>Classification</th>
              <th>Agreement</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $lastEntry = -1;
            $student = '';
            foreach ($this->model->result as $row) {
              if ($lastEntry == -1) {
                $lastEntry = $row['entry'];
                $student = $row['author'];
                $entries = array();
                $entries[$row['classification']] = 1;
                //array_push($entries, $row['classification']);
              } else {
                $thisEntry = $row['entry'];

                if ($thisEntry == $lastEntry) {
                  $student = $row['author'];
                  if (isset($entries[$row['classification']])) {
                    $entries[$row['classification']] = $entries[$row['classification']] + 1;
                  } else {
                    $entries[$row['classification']] = 1;
                  }
                  //array_push($entries, $row['classification']);
                } else {
                  echo "<tr><td>{$student}</td><td><a href='/tutorViewJournal/viewEntry?entryId={$lastEntry}'>{$lastEntry}</a></td><td>";
                  $keys = array_keys($entries);
                  $max = 0;
                  $maxScore = 0;
                  foreach ($keys as $entryKey) {
                    if ($entries[$entryKey] > $max) {
                      $max = $entries[$entryKey];
                      $maxScore = $entryKey;
                    }
                    echo "rating({$entryKey}) count({$entries[$entryKey]}),";
                  }
                  //foreach ($entries as $entry) {
                  //    echo "({$entry}), ";
                  //}
                  echo "</td>";
                  if ($max > 1) {
                    echo "<td class='category'>{$max} assessors scored {$maxScore}</td>";
                  } else {
                    echo "<td></td>";
                  }
                  echo "</tr>";
                  $lastEntry = $thisEntry;
                  $student = $row['author'];
                  $entries = array();
                  $entries[$row['classification']] = 1;
                  //array_push($entries, $row['classification']);
                }
              }
              //$entry = $row["entry"];
              //$id = $row['entry'];
              //echo "<tr><td>{$id}</td><td>{$entry}</td></tr>";
            }
            ?>
          </tbody></table>
        <div id="countEntry"></div>
      </div>
      <div id="journal_entry"></div>   
    </div>


  </body>
</html>
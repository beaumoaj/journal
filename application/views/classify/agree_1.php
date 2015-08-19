<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
header('Pragma: no-cache');
header('Expires: Fri, 30 Oct 1998 14:19:41 GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: text/csv; name="agreementData.csv"');
header('Content-Disposition: attachment; filename="agreementData.csv"');
$studentHash = array();
$lastEntry = -1;
$student = '';
foreach ($this->model->result as $row) {
  if ($lastEntry == -1) {
    $lastEntry = $row['entry'];
    $student = $row['author'];
    $entries = array();
    $entries[$row['classification']] = 1;
    $sun = $row['sun'];
    $dateTime = $row['dateTime'];
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
      //echo "<tr><td>{$student}</td><td><a href='/tutorViewJournal/viewEntry?entryId={$lastEntry}'>{$lastEntry}</a></td><td>{$sun}</td><td>{$dateTime}</td>";
      $keys = array_keys($entries);
      $max = 0;
      $maxScore = 0;
      /*       * * */
      //echo "<td>";

      foreach ($keys as $entryKey) {
        if ($entries[$entryKey] > $max) {
          $max = $entries[$entryKey];
          $maxScore = $entryKey;
        }
        //echo "rating({$entryKey}) count({$entries[$entryKey]}),";
      }
      //foreach ($entries as $entry) {
      //    echo "({$entry}), ";
      //}
      //echo "</td>";
      /*
        if ($max > 1) {
        echo "<td class='category'>{$maxScore}</td>";
        } else {
        echo "<td></td>";
        }
        echo "</tr>";
       * 
       */
      $values = array("user" => $student, "entryId" => $lastEntry, "SUN" => $sun, "date" => $dateTime, "classification" => $maxScore);
      if (!isset($studentHash[$student])) {
        $studentHash[$student] = array();
      }
      $studentHash[$student][$lastEntry] = $values;
      $lastEntry = $thisEntry;
      $student = $row['author'];
      $sun = $row['sun'];
      $dateTime = $row['dateTime'];
      $entries = array();
      $entries[$row['classification']] = 1;
      //array_push($entries, $row['classification']);
    }
  }
  //$entry = $row["entry"];
  //$id = $row['entry'];
  //echo "<tr><td>{$id}</td><td>{$entry}</td></tr>";
}
foreach ($studentHash as $row) {
  $studentClassifications = "";
  $data = null;
  foreach ($row as $v) {
    $data = $v;
    $studentClassifications .= $v["classification"] . ",";
    //echo "{$v["user"]}, {$v["entryId"]}, {$v["SUN"]}, {$v["date"]}, {$v["classification"]}<br/>";
  }
  echo "{$data["user"]},{$data["SUN"]},{$studentClassifications}\n";
}
?>

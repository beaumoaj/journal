<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
  require_once('libs/utf8/utf8.php');
  require_once('libs/utf8/utils/bad.php');
  require_once('libs/utf8/utils/validation.php');
  require_once('libs/utf8_to_ascii/utf8_to_ascii.php');
 * 
 */

function clean($json) {
    #remove all non utf8 characters
    $json = mb_convert_encoding($json, 'UTF-8', 'UTF-8');
    # Remove non printable character (i.e. below ascii code 32).
    $json = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $json);
    $json = empty($json) ? '[]' : $json;
    $search = array('\\', "\n", "\r", "\f", "\t", "\b", "'", '"');
    //$replace = array('\\\\', "\\n", "\\r", "\\f", "\\t", "\\b", "&#039;", "&#034;");
    $replace = array('\\\\', "&#010;", "&#010;", "\\f", "&#009;", "\\b", "&#039;", "&#034;");
    $json = str_replace($search, $replace, $json);
    return $json;
}

header('Content-Type: application/json; charset=UTF-8');
// Build event object
$values = array();
//$values['idProperty'] = 'id';
//$values['label'] = 'label';
//$values['items'] = array();
//$result = "{ data: [";
//$comma = false;
// Add users
foreach ($model->entries as $row) {
    // Add user data
    $value = array();
    //if ($comma == true) {
    //    $result .= ", ";
    //}
    //$result .= "{ id: \"{$row['idclassifications']}\", label: \"{$row['name']}\", text: \"{$row['description']}\"}";
    //$comma = true;
    $value['id'] = clean($row['id']);
    $value['classification'] = clean($row['classification']);
    $value['entry'] = clean($row['entry']);
    $value['username'] = clean($row['username']);
    array_push($values, $value);
}
//$result .= "]}";
// Convert to JSON and print
$result = json_encode($values);
echo $result;
?>


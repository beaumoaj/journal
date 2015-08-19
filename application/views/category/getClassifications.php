<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
header('Content-Type: application/json');
// Build event object
$values = array();
$values['identifier'] = 'id';
$values['label'] = 'label';
$values['items'] = array();
//$result = "{ data: [";
//$comma = false;
// Add users
$value = array();
$value['id'] = "";
$value['label'] = "--- Select ---";
$value['value'] = "";
$value['description'] = "Please select a classification";
array_push($values['items'], $value);

foreach ($model->classifications as $row)
 {
    // Add user data
    $value = array();
    //if ($comma == true) {
    //    $result .= ", ";
    //}
    //$result .= "{ id: \"{$row['idclassifications']}\", label: \"{$row['name']}\", text: \"{$row['description']}\"}";
    //$comma = true;
    $value['id'] = $row['idclassifications'];
    $value['label'] = $row['name'];
    $value['value'] = $row['value'];
    $value['description'] = $row['description'];
    array_push($values['items'], $value);
}
//$result .= "]}";

// Convert to JSON and print
echo json_encode($values);
//echo $result;
?>


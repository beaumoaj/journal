
<script type="text/javascript" src="../script/classificationComponent.js">
    // code to hand data and events
</script>

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function generateEntry($idCode) {
    $dropdownID = "target" . $idCode;
    $descriptionID = "explain" . $idCode;
    echo "<div id=\"{$dropdownID}\"></div>";
    echo "<div id=\"{$descriptionID}\"></div>";
    echo "<script type=\"text/javascript\">wire({$dropdownID}, {$descriptionID});</script>";
}
?>


<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

header('Pragma: no-cache');
header('Expires: Fri, 30 Oct 1998 14:19:41 GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: image/svg+xml; charset=UTF-8');

$white = '#ffffff';
$black = '#000000';
$red = '#ff0000';
$orange = '#FFCC00';// '#FF8000';
$green = '#7CFC00';//#008000';
$width = 60;
$widthMinus = $width - 1;
$centre = 30;
//$height = 60;
$radius = $centre - 1;

function circle($fill, $c, $r) {
  return "<circle cx=\"{$c}\" cy=\"{$c}\" r=\"{$r}\"   
    style=\"fill:{$fill};stroke:#000000;stroke-width:2\" />";
}

$header = "<?xml version=\"1.0\"?>
<svg viewBox=\"0 0 {$width} {$width}\" version=\"1.1\"
    xmlns=\"http://www.w3.org/2000/svg\">";
$path = "<path
   d=\"M {$centre}, 1 A {$radius}, {$radius} 0 0 0 {$centre}, {$widthMinus} L {$centre} {$centre} Z\"
   style=\"fill:{$white}\" />";
$footer = "</svg>";
echo $header;
if ($model->total == 0) {
  $colour = $red;
} else if ($model->total == $model->done) {
  $colour = $green;
} else {
  $colour = $orange;
}
echo circle($colour, $centre, $radius);
//error_log("SVG total {$model->total} done {$model->done}");
if ($model->total != $model->done && $model->done > 0) {
  echo $path;
}
echo $footer;


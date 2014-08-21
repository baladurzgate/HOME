<?php
$um = new UserMap();
$um->appendMap();
$_posX = new Variable('POST','x');
$posX = $_posX->getValue();
$userX = new Variable('SESSION','x');
$userX->setValue($posX);

$_posY = new Variable('POST','y');
$posY = $_posY->getValue();
$userY = new Variable('SESSION','y');
$userY->setValue($posY);

$_color = new Variable('POST','color');
$color = $_color->getValue();
$userColor = new Variable('SESSION','color');
$userColor->setValue($color);
?>
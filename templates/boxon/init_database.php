<?php
	//<!--INIT_DATABASE-->
	
	//BASE DE DONNEE 
	global $db;
	$db = new DataBase('boxon','mysql');
	$db->connect('localhost','root','');
	$db->create('mysql');


?>

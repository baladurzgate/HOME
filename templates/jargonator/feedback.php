<!--FEEDBACK-->
<!--
	$messages
-->
<?php
	global $_TEMPLATE_INC;
	foreach($messages as $s => $m){
		$message=$m;
		$source=$s;
		include ($_TEMPLATE_INC.'/feedback-message.php');
	}
?>

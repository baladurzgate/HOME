<?php
//<!--UTILS-->
	$S=Site::getInstance();
	function pageButton($page,$innerHTML,$class='bt'){
		$url= $S->RequestURL($page);	
		$onclick='window.location.href = "'.$url.'";';
		$output='<button class="'.$class.'" onclick="'.$onclick.'">'.$innerHTML.'</button>'."\n";
		return $output;
	}

?>

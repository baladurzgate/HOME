<!--RACINES-FORM-->
<!--
	$inputs
	$name
	$this->parseButton( action , label , classe )  id = 
	
-->
<span id="feedback-racines"></span>
<form id="form-racines" class="form">
	<div class="form">
		<?php echo $inputs['orthographe'];?>
		<?php echo $inputs['definition'];?>
		<?php echo $inputs['type'];?>
	</div></br>
</form>
	<?php 
	global $S;
	echo $this->parseButton('submit','ENVOYER','bt',$S->parseURLFor('archives_racines'));
	?>

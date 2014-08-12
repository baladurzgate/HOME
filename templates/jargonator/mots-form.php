<!--MOTS-FORM-->
<!--
	$inputs
	$name
	$this->parseButton( action , label , classe )  id = 
	
-->
<span id="feedback-mots"></span>
<form id="form-mots" class="form">
	<div class="form">
		<?php echo $inputs['orthographe'];?>
		<?php echo $inputs['definition'];?>
	</div></br>
</form>
	<?php 
	global $S;
	echo $this->parseButton('submit','ENVOYER','bt',$S->parseURLFor('archives'));
	?>

<!--COMPTES-FORM-->
<!--
	$inputs
	$name
	$this->parseButton( action , label , classe )  id = 
	
-->
<span id="feedback-comptes"></span>
</br>

<fieldset id="inscription">
	<legend>Inscription</legend>
	<form id="form-comptes" class="form">
		<span>username : </span>
		<div class="form">
			<?php echo $inputs['username'];?>
		</div>
		<span>password : </span>
		<div class="form">
			<?php echo $inputs['password'];?>
		</div>
		<span>email : </span>
		<div class="form">
			<?php echo $inputs['email'];?>
		</div>
	</form>
		<?php 
			global $S,$urls;
			echo $this->parseButton('submit','E N V O Y E R','bt',$S->parseURLFor('confirmation-compte'));
			
		?>
	</br></br><a href="<?php echo $urls['charte']?>">lire la charte de confidentialité</a>
</fieldset>

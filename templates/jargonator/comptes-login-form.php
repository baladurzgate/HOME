<!-- COMPTES-LOGIN-FORM-->
<!--
	$inputs
	$name
	$this->parseButton( action , label , classe )  id = 
	
-->
<span id="feedback-comptes"></span>
</br>

<fieldset id="inscription">
	<legend>login</legend>
	<form id="form-comptes" class="form">
		<span>username : </span>
		<div class="form">
			<?php echo $inputs['username'];?>
		</div>
		<span>password : </span>
		<div class="form">
			<?php echo $inputs['password'];?>
		</div>
	</form>
		<?php 
			global $S;
			$header = 'home';
			if($S->getPageRequest()!==false){
				$header = $S->getPageRequest();
			}
			echo $this->parseButton('login','L O G I N','bt',$S->parseURLFor($header));
		?>
</fieldset>

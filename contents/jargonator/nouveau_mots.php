<!--NOUVEAU_MOTS-->
<fieldset id="inscription">
	<fieldset id="">
		<legend>mots</legend>
		<div id="mots" class="randomWord"></div>
	</fieldset>
	</br>
	<span id="commandes"> 
		<button id="bt-generer" class="bt" onclick="generateRandomWords()">GENERER</button>
		<button id="bt-definir" class="bt" onclick="lock_word();">DEFINIR</button></br>
	</span>
	<div id="form" style="display:none;">
		<?php $mots->appendForm(); ?>
		<button id="bt-annuller" class="bt" onclick="unlock_word();">ANNULER</button></br>
	</div>
</fieldset>

<script type="text/javascript">
	function lock_word(){
		var commandes = document.getElementById("commandes");
		commandes.style.display="none";
		var word = document.getElementById("mots");
		word.className="lockedWord";
		var input=document.getElementById("channel-orthographe");
		input.value=word.innerHTML;
		var form=document.getElementById("form");
		form.style.display="block";
	}
	function unlock_word(){
		var commandes = document.getElementById("commandes");
		commandes.style.display="block";
		var word = document.getElementById("mots");
		word.className="randomWord";
		var input=document.getElementById("channel-orthographe");
		input.value="";
		var form=document.getElementById("form");
		form.style.display='none';
	}
</script>

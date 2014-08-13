<!--NOUVEAU_MOTS-->
<fieldset id="inscription" onload="jargonator('mots','<?php echo $S->RequestURL('jargonator');?>');">
	<fieldset id="">
		<legend>orthographe</legend>
		<div id="orthographe" class="jargonator"></div>
	</fieldset>
	<fieldset id="">
		<legend>etymologie</legend>
		<div id="etymologie" class="etymologie"></div>
	</fieldset>
	</br>
	<span id="commandes"> 
		<button id="bt-generer" class="bt" onclick="jargonator('mots','<?php echo $S->RequestURL('jargonator');?>');">GENERER</button>
		<button id="bt-definir" class="bt" onclick="lock_word();">DEFINIR</button></br>
	</span>
	<div id="form" style="display:none;">
		<?php $mots->appendForm(); ?>
		<button id="bt-annuller" class="bt" onclick="unlock_word();">ANNULER</button></br>
	</div>
</fieldset>

<script type="text/javascript">
	jargonator('mots','<?php echo $S->RequestURL('jargonator');?>');
	function lock_word(){
		var commandes = document.getElementById("commandes");
		commandes.style.display="none";
		var word = document.getElementById("orthographe");
		word.className="lockedWord";
		var input_orth=document.getElementById("channel-orthographe");
		input_orth.value=word.innerHTML;		
		var etymologie = document.getElementById("etymologie");
		var input_etym=document.getElementById("channel-etymologie");
		input_etym.value=etymologie.innerHTML;
		var form=document.getElementById("form");
		form.style.display="block";
	}
	function unlock_word(){
		var commandes = document.getElementById("commandes");
		commandes.style.display="block";
		var word = document.getElementById("orthographe");
		word.className="jargonator";
		var input=document.getElementById("channel-orthographe");
		input.value="";
		var input_etym=document.getElementById("channel-etymologie");
		input_etym.value="";
		var form=document.getElementById("form");
		form.style.display='none';
	}
</script>

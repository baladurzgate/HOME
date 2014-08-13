<!--NOUVELLE_RACINE-->
<?php 
$types = array('prefixe','radical','suffixe');
$randomType = $types[rand(0,count($types)-1)];
global $S;
?>
<fieldset id="inscription">
	<fieldset id="">
		<legend>racine</legend>
		<div id="racine" class="jargonator"></div>
	</fieldset>
	</br>
	<span id="commandes"> 
		<button id="bt-generer" class="bt" onclick="jargonator('<?php echo $randomType; ?>','<?php echo $S->RequestURL('jargonator');?>');">GENERER</button>
		<button id="bt-definir" class="bt" onclick="lock_word();">DEFINIR</button></br>
	</span>
	<div id="form" style="display:none;">
		<input id="type" type="hidden" value="<?php echo $randomType?>">
		<?php $racines->appendForm(); ?>
		<button id="bt-annuller" class="bt" onclick="unlock_word();">ANNULER</button></br>
	</div>
</fieldset>

<script type="text/javascript">
	function lock_word(){
		var commandes = document.getElementById("commandes");
		commandes.style.display="none";
		var word = document.getElementById("racine");
		word.className="lockedWord";
		var input_orth=document.getElementById("channel-orthographe");
		var input_type=document.getElementById("channel-type");
		var type=document.getElementById("type");
		input_orth.value=word.innerHTML;
		input_type.value=type.value;
		var form=document.getElementById("form");
		form.style.display="block";
	}
	function unlock_word(){
		var commandes = document.getElementById("commandes");
		commandes.style.display="block";
		var word = document.getElementById("racine");
		word.className="jargonator";
		var input_orth=document.getElementById("channel-orthographe");
		input_orth.value="";
		var form=document.getElementById("form");
		form.style.display='none';
	}
</script>

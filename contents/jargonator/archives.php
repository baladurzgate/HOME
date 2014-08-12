<!--ARCHIVES-->
<fieldset id="cadre">
	<legend>dictionnaire</legend>
	<div>
	<?php
		$selection = $mots->sortByChannel($mots->all(),'timestamp');
		$mots->appendPosts($mots->sortByChannel($mots->all(),'orthographe'));
	?>
	</div>
</fieldset>

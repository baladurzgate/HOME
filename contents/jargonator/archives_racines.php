<!--ARCHIVES-->
<fieldset id="cadre">
	<legend>dictionnaire</legend>
	<div>
	<?php
		$selection = $racines->sortByChannel($racines->all(),'timestamp');
		$racines->appendPosts($racines->sortByChannel($racines->all(),'orthographe'));
	?>
	</div>
</fieldset>

<!--MOTS-POST-->
<!--
	$output
	$values
	$serial
-->
<div class="post">
	<p class="titre2contenu">
		<a href="<?php echo $outputs['guid'];?>"><?php echo $outputs['post_title'];?></a>
	</p>
	<div class="contenuinterieur">
		<p><?php echo $outputs['post_content'];?></p>
	</div>
	<p class="contenuinfos">
	<?php echo $outputs['post_date'];?><a href="http://localhost/HOME/wordpress/?cat=1" title="Voir tous les articles dans Uncategorized" rel="category">Uncategorized</a>
	</p>
</div>

<?php
//echo $this->display($outputs);

?>
</br></br>
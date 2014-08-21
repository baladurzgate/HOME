<!--MOTS-POST-->
<!--
	$output
	$values
	$serial
-->
<div id="<?php echo $serial;?>" class="mots">
	<a href="<?php echo $outputs['guid'];?>"><b><span id="channel-orthographe"><?php echo $outputs['post_title'];?></span></b></a></br>  
	<i><span id="channel-etymologie"><?php echo $outputs['post_content'];?></span></i></br>  
	<i><span id="channel-definition"><?php echo $outputs['post_author'];?></span></i></br>
	<!--<span id="channel-author"><?php //echo $outputs['author'];?></span></br>-->
</div>

<?php
echo $this->display($outputs);

?>
</br></br>
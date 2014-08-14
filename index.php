<?php 
	//<!--INDEX-->
	//le fichier le plus important du site :
	require('init.php');
	
	//style et meta données
	require('HEAD.php');
?>
		<div id="site">
			<?php 
				if(file_exists($_TEMPLATE_PHP)){
					include_once($_TEMPLATE_PHP);
				}else{
				
				}
			?>
		</div>
<?php 
	//les balises scripts 
	require($root.'/FOOT.php')
?>

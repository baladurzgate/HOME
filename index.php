<!--INDEX-->
<?php
	//le fichier le plus important du site :
	require('init.php');
	
	//style et meta données
	require('HEAD.php');
?><body>
		<div id="site">
			<?php 
				if(file_exists($_TEMPLATE_PHP)){
					include($_TEMPLATE_PHP);
				}else{
				
				}
			?>
		</div>
		
	</body>
<?php 
	//les balises scripts 
	require($root.'/FOOT.php')
?>

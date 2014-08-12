<!-- HOME -->
<?php 	include($_TEMPLATE_INC.'init_database.php'); ?>
<div id="header"><!--marge superieure--><?php include($_TEMPLATE_INC.'/header.php');?></div>
<div id="main">
	<div id="banniere"><!--Templates/banniere.html--><?php include($_TEMPLATE_INC.'/banniere.html');?></div>
	<div id="menuH"><!--Templates/menuH.html--><?php if($_PAGE_NAME=='home'){include($_TEMPLATE_INC.'/menuH.html');}?></div>
	<div id="page">
		<?php ;include $controledPage;?>
	</div>
</div>
<div id="footer"><!--marge inferieure--><?php include($_TEMPLATE_INC.'/footer.php');?></div>
<script>
// script de la page 
</script>
<!-- /HOME -->

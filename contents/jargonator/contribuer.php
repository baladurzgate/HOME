<?php
	$r=$racines->getPostMap();
	$m=$mots->getPostMap();
	$nbr= count($r);
	$nbm=count($m);
	$combinaisonsPossibles=pow($nbr,5);
	$infor="";
	$infom=$nbm.'  mots  '.$combinaisonsPossibles.'  cobinaisons possibles';
	$mbtclass='bt';
	$bt_mots;
	$bt_mots_titre='______N O U V E A U___M O T S______';
	$bt_racines;
	$bt_racines_titre='_____N O U V E L L E___R A C I N E____';
	
	if($nbr<2){
		$infor = 'aucune racines dans le dico';
		$mbtclass='locked_bt';
		$infom='il doit y avoir au moins 2 racines dans le dico';
		$bt_mots='<button class="'.$mbtclass.'">'.$bt_mots_titre.'</button>';
	}else{
		$infor=$nbr.' racines ';
		$bt_mots=$S->parsePageButton('nouveau_mots',$bt_mots_titre,$mbtclass);
	}
	$bt_racines=$S->parsePageButton('nouvelle_racine',$bt_racines_titre,'bt');

	
	$mbtclass='locked_bt';
?>
<!--CONTRIBUER-->
<fieldset id="inscription">
	<legend>Contribuer</legend>
	<?php echo $bt_mots;?></br>
	<span id="nb_mots"><?php echo $infom;?></span></br></br>
	<?php echo $bt_racines;?></br>
	<span id="nb_racines"><?php echo $infor; ?></span>
</fieldset>
</div>

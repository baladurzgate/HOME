<?php	
	//<!--CONFIG-->
	
	//ajout des chemins detailles
	//chemins serveur :
	$S->addPath("S","src",$root.'/src/'); 
	$S->addPath("S","templates",$root.'/templates/');
	$S->addPath("S","contents",$root.'/contents/');
	$S->addPath("S","data",$root.'/data/');
	$S->addPath("S","databases",$root.'/data/databases/');
	$S->addPath("S","sessions",$root.'/data/sessions/');
	$S->addPath("S","logs",$root.'/data/logs/'); 
	$S->addPath("S","hg",$root.'/HG/');
	//chemins client :
	$S->addPath("C","src",$url.'src/');
	$S->addPath("C","templates",$url.'templates/');
	$S->addPath("C","contents",$url.'contents/');
	$S->addPath("C","data",$root.'/data/');
	$S->addPath("C","databases",$root.'/data/databases/');
	$S->addPath("C","sessions",$root.'/data/sessions/');
	$S->addPath("C","logs",$root.'/data/logs/'); 
	$S->addPath("C","hg",$url.'HG/');
	
	//template content et page par default : 
	$S->setDefaultTemplate("jargonator");
	$S->setDefaultContent("jargonator");
	$S->setDefaultPage("home");
	
	//Editeur HG:
	$S->addPath("HG","edit","http://localhost/BOXON_SandBox/Site/HG/?pages");
?>

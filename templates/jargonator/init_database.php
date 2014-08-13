<?php
	//<!--INIT_DATABASE-->
	
	//BASE DE DONNEE 
	global $db;
	$db1 = new DataBase('jargonator');
	$db1->create('xml');
	
	//COMPTES UTILISATEUR
	global $comptes;
	$comptes=new AcountSystem('comptes');
	$db1->addTable($comptes);
	
	//TABLEAU MOTS
	global $mots;
	$mots = new Table("mots");
	if(!$mots->allreadyExist()){
		$channels = array();
		$channels['orthographe']=new Channel("orthographe",array("type"=>"hidden","access"=>"form-archives","mandatory"=>"true"));
		$channels['definition']=new Channel("definition",array("type"=>"text","access"=>"form-archives","mandatory"=>"true"));
		$mots ->addchannels($channels);
	}
	$mots->linkTo($comptes);
	$db1->addTable($mots);
	
	//TABLEAU RACINES
	global $racines;
	$racines = new Table("racines");
	if(!$racines->allreadyExist()){
		$channels = array();
		$channels['orthographe']=new Channel("orthographe",array("type"=>"hidden","access"=>"form-archives","mandatory"=>"true"));
		$channels['definition']=new Channel("definition",array("type"=>"text","access"=>"form-archives","mandatory"=>"true"));
		$channels['type']=new Channel("type",array("type"=>"hidden","access"=>"form-archives","mandatory"=>"true"));
		$racines ->addchannels($channels);
	}
	$racines->linkTo($comptes);
	$db1->addTable($racines);
	
	//OPERATIONS DE CONTROL 
	$User = $comptes->getCurrentUser(); 
	
	$controledPage=$_PAGE_INC;
	$restricted_pages_list=array('contribuer','nouveau_mots');
	
	if($User==false){
		$controledPage = $S->controlAccess($restricted_pages_list,'control');
	}
	
	global $_PAGE_NAME;
	
	//urls du site
	global $urls;
	$urls=array();
	$urls['nouveau_compte'] = $S->parseURLFor('nouveau_compte');
	$urls['contribuer'] = $S->parseURLFor('contribuer');
	$urls['archives'] = $S->parseURLFor('archives');
	$urls['login'] = $S->parseURLFor('login');
	$urls['nouveau_mots'] = $S->parseURLFor('nouveau_mots');
	$urls['about'] = $S->parseURLFor('about');
	$urls['charte'] = $S->parseURLFor('charte');
	
	//liste des pages nécéssitant de se logger
	
	$mots->extractMap();
	$racines->extractMap();
?>

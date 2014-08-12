<!--INIT-->
<?php
	//desactivation des erreurs php
	//error_reporting(0);	

	// recuperation des infos sur le serveur
	$serverName = $_SERVER['SERVER_NAME'];
	$serverSoftware = $_SERVER['SERVER_SOFTWARE'];
	$serverRoot = $_SERVER['DOCUMENT_ROOT'];
	$serverSelf = $_SERVER['PHP_SELF'];
	$fileURL=str_replace("index.php","",$serverSelf);

	//la variable root renvoi l'adresse absolue du site sur le serveur et url l'adresse relative pour le navigateur
	$root;
	$url;
	if($serverName=='localhost'){
		$root = dirname(__FILE__);
		$url = "http://".$serverName.$fileURL;
		
	}else{
		$root = dirname(__FILE__);
		$url = "http://".$serverName.$fileURL;
	}
	//Importation de la classe Mere
	require($root.'/src/Site.class.php');
	
	//base de donnee
	//require($root.'dbConfig.php');
	
	//construction de l'objet singleton $S issue de la classe Site
	global $S;
	$S=Site::getInstance();
	$S->setName("jargonator");
	$S->ROOT = $root;
	$S->URL = $url;
	
	//CONFIGURATION  :
	include('config.php');
	
	
	//Importation de la classe qui sert de base à toute les autres
	require($S->paths["S"]['src'].'Component.class.php');
	
	//Importation du Log
	require($S->paths["S"]['src'].'Alert.class.php');
	require($S->paths["S"]['src'].'Log.class.php');
	require($S->paths["S"]['src'].'Feedback.class.php');

	//function declanchee lors d'une erreur php
	function phpErrorToLog($error){
		$alertPHP = new Alert($error,'error');
		$S->addToLog($alertPHP);
	}

	//Importation des autres classes
	require($S->paths["S"]['src'].'Variable.class.php');
	require($S->paths["S"]['src'].'Map.class.php');
	require($S->paths["S"]['src'].'WebObject.class.php');
	require($S->paths["S"]['src'].'Content.class.php');
	require($S->paths["S"]['src'].'DataBase.class.php');
	require($S->paths["S"]['src'].'Page.class.php');
	require($S->paths["S"]['src'].'Template.class.php');
	require($S->paths["S"]['src'].'Visitor.class.php');
	require($S->paths["S"]['src'].'File.class.php');
	require($S->paths["S"]['src'].'Dir.class.php');
	require($S->paths["S"]['src'].'Channel.class.php');
	require($S->paths["S"]['src'].'Table.class.php');
	require($S->paths["S"]['src'].'XML.class.php');
	require($S->paths["S"]['src'].'Post.class.php');
	require($S->paths["S"]['src'].'Filter.class.php');
	require($S->paths["S"]['src'].'Hg/HgPage.class.php');
	require($S->paths["S"]['src'].'Hg/HgObject.class.php');
	require($S->paths["S"]['src'].'Acount/UserAcount.class.php');
	require($S->paths["S"]['src'].'Acount/AcountSystem.class.php');
	
	//initialisation de l'objet Site (et activation du log)
	$S->init();
	

	//variables globales facilement accessibles :

	//template
	global $_TEMPLATE;
	global $_TEMPLATE_INC,$_TEMPLATE_LINK,$_TEMPLATE_JS,$_TEMPLATE_PHP;
	
	$_TEMPLATE = $S->template;
	$_TEMPLATE_INC = $S->template->getServerPath('dir');
	$_TEMPLATE_PHP = $S->template->getServerPath('php');
	$_TEMPLATE_LINK = $S->template->getClientPath('dir');
	$_TEMPLATE_JS = $S->template->getClientPath('js');
	
	//content
	global $_CONTENT;
	global $_CONTENT_INC,$_CONTENT_LINK,$_CONTENT_JS;
	
	$_CONTENT = $S->content;
	$_CONTENT_INC = $S->content->getServerPath('dir');
	$_CONTENT_LINK = $S->content->getServerPath('dir');
	$_CONTENT_JS = $S->template->getClientPath('js');
	

	
	//page
	global $_PAGE;
	global $_PAGE_URL,$_PAGE_INC,$_PAGE_NAME;
	
	$_PAGE = $S->page;
	$_PAGE_URL = $S->page->getURL();
	$_PAGE_INC = $S->page->getINC();
	$_PAGE_NAME = $S->page->getName();


	//visitor
	global $_VISITOR;
	
	$_VISITOR = $S->getVisitor;
	
	//hg
	global $_HG_EDITOR;
	$_HG_EDITOR="http://localhost/BOXON_SandBox/Site/HG/?pages";
	$S->setPath('HG','Edit',$_HG_EDITOR);
	
	
	//permet de filtrer le log en fonction du type d'alerte (rends le site plus rapide)
	$S->filterLog('bypass');
	
	$S->createHgPageEditList();

	/*$con=mysql_connect($user, $server, $password)or die(mysql_error()) ;
	mysql_select_db($dbname)or die(mysql_error()) ;*/
	
	//fonction php utiles
	require($root.'/src/utils.php');
?>

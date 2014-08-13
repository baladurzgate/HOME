<?php
//<!--JARGONATOR-->
include($_TEMPLATE_INC.'init_database.php');
$_request = new Variable('POST','req');
$_type = new Variable('POST','type');
$_orth = new Variable('POST','orth');
$type = $_type->getValue();
$orth = $_orth->getValue();
$request = $_request->getValue();
global $racines;
if($type!==false){
	if($request!==false){
		switch($request){
			case 'check':
				if($orth!==false){
					if($racines->getPostsByValue('orthographe',$orth)==false){
						echo 'valid';
					}
				}else{
					echo 'taken';
				}
			break;
			case 'generate':
				$nOrth = generer();
				while($mots->getPostsByValue('orthographe',$nOrth)!==false){
					$nOrth=generer();
				}
				echo $nOrth;
			break;
		}
	}
}

function generer(){
	global $racines;
	$prefixes = $racines->getPostsByValue('type','prefixe');
	$radicaux = $racines->getPostsByValue('type','radical');
	$suffixes = $racines->getPostsByValue('type','suffixe');
	if(count($prefixes)>0&&count($suffixes)>0){
		$combinaison=array();
		$randomP = $prefixes[rand(0,count($prefixes)-1)];
		array_push($combinaison,$randomP);
		for($i=0;$i<rand(0,rand(0,3));$i++){
			$R = $radicaux[rand(0,count($radicaux)-1)];
			if($i>0){
				if($combinaison[$i-1]!==$R){
					array_push($combinaison,$R);
				}
			}else{
				array_push($combinaison,$R);
			}
		}
		$orthographe="";
		$etymologie="";
		$calls="";
		$randomS = $suffixes[rand(0,count($suffixes)-1)];
		array_push($combinaison,$randomS);
		foreach ($combinaison as $r){
			$orthographe.=$r->getValue('orthographe');
			//$etymologie.=$r->getValue('orthographe').'['.$r->getValue('definition').']';
			$etymologie.='['.$r->getValue('definition').']';
			$calls.=$r->getSerial;
		}
		 $orthographe=strtolower($orthographe);
		 $orthographe=ucfirst($orthographe);
		return $orthographe.'|'.$etymologie.'|'.$calls;
	}
}
?>

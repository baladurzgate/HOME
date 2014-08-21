<?php 
	//--UTILS--
	$S=Site::getInstance();
	function pageButton($page,$innerHTML,$class='bt'){
		$url= $S->RequestURL($page);	
		$onclick='window.location.href = "'.$url.'";';
		$output='<button class="'.$class.'" onclick="'.$onclick.'">'.$innerHTML.'</button>'."\n";
		return $output;
	}
	
	//todo = rendre ces fonctions recursives pour lire les sous array
	function stringToArray($string,$split1,$split2){
		$output = array();
		if($string!==NULL){
			$varSplit = explode($split1,$string);
			foreach($varSplit as $v){
				if($v!==NULL||$v!==''){
					$equalSplit = explode($split2,$v);
					if(count($equalSplit)>1){
						$output[$equalSplit[0]]=$equalSplit[1];
					}else{
						$output[$equalSplit[0]]="";
					}
				}
			}
			return $output;
		}
		return false;
	} 
	function arrayToString($array,$split1,$split2){
		$string="";
		$output="";
		if(is_array($array)==true){
			foreach($array as $n => $v){
				$string.=$n.$split2.$v.$split1;
			}
			$output = substr($string, 0, -1);	
		}
		return $output;
	}
?>
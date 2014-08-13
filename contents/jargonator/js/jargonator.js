function jargonator($type,$ask){
	var orth,request,params,xmlhttp;
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	var consonnesDebut = ["b","b","br","bl","c","cr","c","ch","c","d","d","d","dr","f","fl","fr","g","gl","gr","gu","j","j","h","kr","k","l","m","m","n","n","p","p","p","p","p","pr","pl","qu","r","r","r","s","s","s","s","s","s","sk","sc","sr","sl","t","tr","t","t","t","t","t","t","t","v","vl","vr","x","z"];
	var consonnesMilieu = ["b","b","br","bl","c","cr","c","ch","c","c","c","d","d","d","dr","f","ff","f","f","f","f","f","f","fl","fr","g","gl","gr","gn","gu","j","h","kr","k","l","ll","m","mm","nn","n","pr","pl","ps","p","qu","r","r","rr","ss","s","s","s","s","s","sc","sk","st","s","sp","sr","sl","tt","tr","t","v","vl","vr","x","z"];
	var consonnesFin = ["ce","ca","ci","co","de","di","do","da","fe","he","ke","ko","ki","le","la","li","me","me","ne","ni","pe","pe","r","ra","re","se","so","sa","te","ti","to","ve","va","vi","xe","ze"];
	var voyellesDebut = ["a","e","eu","i","o","u","on","ou","an","ai","eau"];
	var voyellesMilieu = ["a","a","a","a","ei","e","e","e","eu","i","i","i","o","o","oa","o","u","on","on","ou","en","an","ai","ui","eau","ion","iou"];
	var voyellesFin = ["a","a","a","a","e","e","e","e","i","i","i","o","o","o","o","u","on","on","ou","oux","en","an","eau","ion","iou"];
	function randomIndex ($Array){
		var result = 0;
		if($Array.length>0){
			result = Math.round(Math.random()*$Array.length-1);
			if(result<0){
				result=0;
			}
			return result;
		}
		return false;
	}
	function generate(){
		switch ($type){
			case 'prefixe':
				if(Math.round(Math.random()*3)+1!==3){
					orth=consonnesDebut[randomIndex(consonnesDebut)]+voyellesMilieu[randomIndex(voyellesMilieu)];
				}else{
					orth=voyellesDebut[randomIndex(voyellesDebut)];
				}
				request='check';
				params ='type='+$type+'&req='+request+'&orth='+orth;
			break;
			case 'radical':
				orth=consonnesMilieu[randomIndex(consonnesMilieu)]+voyellesMilieu[randomIndex(voyellesMilieu)];
				request='check';
				params ='type='+$type+'&req='+request+'&orth='+orth;
			break;
			case 'suffixe':
				if(Math.round(Math.random()*3)+1!==3){
					orth=consonnesFin[randomIndex(consonnesFin)];
				}else{
					orth=consonnesMilieu[randomIndex(consonnesMilieu)]+voyellesFin[randomIndex(voyellesFin)];
				}		
				request='check';
				params ='type='+$type+'&req='+request+'&orth='+orth;
			break;
			case 'mots':
				request='generate';
				params ='type='+$type+'&req='+request;
			break;
			default:
			
			break;
		}
	}
	function firstToUpperCase( str ) {
		return str.substr(0, 1).toUpperCase() + str.substr(1);
	}
	
	function appendJargonator($orth){
		var orthEnMaj=firstToUpperCase($orth);
		var cibles = document.getElementsByClassName('jargonator');
		for (var i =0;i<cibles.length;i++){
			cibles[i].innerHTML  = orthEnMaj;
		}	
	}
	
	xmlhttp.onreadystatechange = function(){
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
			generate();
			if($type!=='mots'){
				while(xmlhttp.responseText.trim()!=='valid'){
					generate();
					appendJargonator(orth);
					console.log(xmlhttp.responseText.trim());
					xmlhttp.open("POST",$ask,false);
					xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xmlhttp.send(params);
				}
				appendJargonator(orth);
			}else{
				generate();
				var response = xmlhttp.responseText.trim();
				splitResponse=response.split('|');
				var orthographe = splitResponse[0];
				var etymologie = splitResponse[1];
				var calls = splitResponse[2];
				document.getElementById('orthographe').innerHTML=orthographe;
				document.getElementById('etymologie').innerHTML=etymologie;
			}
		}
	}
	generate();
	xmlhttp.open("POST",$ask,false);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send(params);
}

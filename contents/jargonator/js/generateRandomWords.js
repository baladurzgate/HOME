/*----GENERATERANDOMWORDS-----
function generateRandomWords(){
	var cibles = document.getElementsByClassName('randomWord');
	function randomWord(){
		var consonnesDebut = ["b","b","br","bl","c","cr","c","ch","c","d","d","d","dr","f","fl","fr","g","gl","gr","gu","j","j","h","kr","k","l","m","m","n","n","p","p","p","p","p","pr","pl","qu","r","r","r","s","s","s","s","s","s","sk","sc","sr","sl","t","tr","t","t","t","t","t","t","t","v","vl","vr","x","z"];
		var consonnesMilieu = ["b","b","br","bl","c","cr","c","ch","c","c","c","d","d","d","dr","f","ff","f","f","f","f","f","f","fl","fr","g","gl","gr","gn","gu","j","h","kr","k","l","ll","m","mm","nn","n","pr","pl","ps","p","qu","r","r","rr","ss","s","s","s","s","s","sc","sk","st","s","sp","sr","sl","tt","tr","t","v","vl","vr","x","z"];
		var consonnesFin = ["c","c","c","c","d","d","d","d","f","h","k","k","l","me","me","n","n","p","p","r","ra","re","s","s","s","t","t","t","v","v","v","x","z"];
		var voyellesDebut = ["a","e","eu","i","o","u","on","ou","an","ai","eau"];
		var voyellesMilieu = ["a","a","a","a","ei","e","e","e","eu","i","i","i","o","o","oa","o","u","on","on","ou","en","an","ai","ui","eau","ion","iou"];
		var voyellesFin = ["a","a","a","a","e","e","e","e","i","i","i","o","o","o","o","u","on","on","ou","oux","en","an","eau","ion","iou"];
		var nom = "";
		function randomIndex ($Array){
			var result = 0;
			if($Array.length){
				result = Math.round(Math.random()*$Array.length-1);
				if(result<0)result=0;
				return result;
			}
			return false;
		}
		
		var randomNumberOfSyllabes = Math.round(Math.random()*Math.random()*5)+2;	
		for (var i = 0;i<randomNumberOfSyllabes;i++){
			if(i==0){
				if(Math.round(Math.random()*3)+1!=3){
						nom+=consonnesDebut[randomIndex(consonnesDebut)]+voyellesMilieu[randomIndex(voyellesMilieu)];
				}else{
						nom+=voyellesDebut[randomIndex(voyellesDebut)];
				}
			}else if(i!=randomNumberOfSyllabes-1){
				nom+=consonnesMilieu[randomIndex(consonnesMilieu)]+voyellesMilieu[randomIndex(voyellesMilieu)];
			}else{
				if(Math.round(Math.random()*3)+1!=3){
					nom+=consonnesFin[randomIndex(consonnesFin)];
				}else{
					nom+=consonnesMilieu[randomIndex(consonnesMilieu)]+voyellesFin[randomIndex(voyellesFin)];
				}
			}
		}
		var randomGender = Math.round(Math.random()*1);	
		var groupeNominal = "";
		if(randomGender==1){
			groupeNominal="un "+nom;
		}else{
			groupeNominal="une "+nom+"e";
		}
		var resultat = groupeNominal;
		function firstToUpperCase( str ) {
			return str.substr(0, 1).toUpperCase() + str.substr(1);
		}
		var nomEnMaj=firstToUpperCase(nom);
		console.log(resultat);
		return nomEnMaj;
	}
	for (var i =0;i<cibles.length;i++){
		cibles[i].innerHTML  = randomWord();
	}	
}
generateRandomWords();

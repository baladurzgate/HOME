<?php
	/*
						* * * * * * * * * * * * * * *
						*                           *
						*     C O M P O N E N T     *
						*                           *
						* * * * * * * * * * * * * * *
	*/
class Component {

	//variable
	private $S;
	private $type;
	private $ID;
	private $mapIndex;
	private $blocked;
	private $relatedToLog;
	
	//construction 
    function __construct($type){
		//on trouve le site
		$this->S=Site::getInstance();
		//on defini le type
		$this->type = $type;
		//on genere une nouvelle id
		$this->ID = $this->S->generateID();
		//on ajoute le composant au Site
		$this->mapIndex = $this->S->add($this);
		//par default le composant ne recoit pas de traitement special 
		$this->relatedToLog = false;
		
    }
	
	//GETTER
	public function __get($property) {
		if (property_exists($this, $property)) {
		  return $this->$property;
		}
	}

	//SETTER
	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
	}
	public function getType(){
		return $this->type;
	}	
	public function changeType($t){
		$this->type = $t;
	}
	
	//log, ajoute le type du Component au debut de l'alert;
	public function Log($content,$alertType='event'){
		// la fonction log ne peut s'appliquer aux alertes (cela creerait une boucle infinie qui engloutirait l'humanitee!)
		if($this->type!='alert'){
			//on ajoute le type du Component au debut de l'alert. ex : file->Log("erreur")  affiche dans le log : "_[file]-->erreur"
			//ca le rend plus cool aussi 
			$coolContent = "_[<b>".$this->type."-".$this->ID."</b>]-->".htmlspecialchars ($content);
			$oAlert = new Alert($coolContent,$alertType,$this->ID,$this->type."_alert");
			//on ajoute l'alerte au log du site
			$this->S->addToLog($oAlert);
		}
	}
	
	//verifi le type du component
	public function isType($type){
		return $type == $this->type;
	}

}
?>

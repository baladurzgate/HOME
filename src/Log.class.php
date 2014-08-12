<?php
/*
					* * * * * * * * * * * * * * *
					*                           *
					*          L O G            *
					*                           *
					* * * * * * * * * * * * * * *
*/
class Log extends Component {

	//variables 
	private $url;
	private $file;
	private $JsConsole;
	private $listOfAlert;

	//constructor
    function __construct() {
		parent::__construct("log");
		//on creer un nouveau fichier Log.txt dans Log/
		$this->isRelatedToLog=true;
		
		$url = $this->S->paths['S']['Log'].'/Log.html';
		$this->file =  new File($url,false);
		$this->file->isRelatedToLog=true;
		$this->file->create(false);
		$this->listOfAlert = array();
		$this->JsConsole = "";
		$this->updateFile();
		$this->updateJsConsole();
    }
	
	// ajoute une alerte a listOfAlert
	public function addAlert($alert){
		//on ajoute l alerte a la liste
		array_push($this->listOfAlert,$alert);
	}
	
	// actualise log.txt 
	public function updateFile(){
		$style='<style>body{font-family:arial;font-size:12;}</style>';
		if(gettype($this->listOfAlert)=='array'){
			$toWrite="<html>".$style."<body>";
			foreach($this->listOfAlert as $alert){
				$contentHTML=$alert->display();
				$toWrite.=$contentHTML."</br>";
			}
			$toWrite.="</body></html>";
			if($this->file->write($toWrite,'over',false)){
				return true;
			}
		}
		
		return false;
	}
	
	// permet d'afficher le log dans la console js de firefox (necessite de mette "<script> <?php echo $S->Log->getJsConsole();> </script>" dans <head>)
	public function updateJsConsole(){
		$toJsConsole="";
		if(gettype($this->listOfAlert)=='array'){
			foreach($this->listOfAlert as $alert){
				$toJsConsole.="console.log('".$alert->getContent()."');";
			}
		}
		$this->JsConsole = $toJsConsole;
	}
	
	//renvoi la string JsConsole
	public function getJsConsole(){
		return $this->JsConsole;
	}
}
?>

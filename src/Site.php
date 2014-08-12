<?php
	/*
													* * * * * * * * * *
													*                 *
													*   ! S I T E  !  *
													*                 *
													* * * * * * * * * *
	*/
class Site {

	//variables 
	private static $_instance = null;
	private $name;
	private $serverName;
	private $serverSoftware;
	private $serverRoot;
	private $ROOT;
	private $page;
	private $template;
	private $content;
	private $visitor;
	private $generator = 0;
	private $paths = array();
	private $map = array();
	private $Log;
	private $logActivated;
	private $infos;
	private $acountSystem;
	private $default_template;
	private $default_content;
	private $default_page;
	private $pageRequest;
	private $logfilter;
	
//___________________________________________[ C O N S T R U C T O R ]___________________________________________|
	
	//function déclanchée � la cr�ation d'un objet Site;
    function __construct(){
		$this->paths['S']= array();
		$this->paths['C']= array();
		$this->logActivated=false;

    }
	
	//construction du Singleton (classe qui ne peut avoir qu'une seule instance)
	public static function getInstance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new Site();
		}
		return self::$_instance;
	}
	
	//initialisation des données
	public function init(){
		//on active le log;
		$this->activateLog();
		$this->toLog("initializing Site...","process");
		
		//on reccupere la valeur Template pass�e potentiellement par GET
		$this->setTemplateFromGET();
		
		//on reccupere la valeur Template pass�e potentiellement par GET
		$this->setContentFromGET();
		
		//on reccupere la valeur Page pass�e potentiellement par GET
		$this->setPageFromGET();
		
		//on creer un objet client
		$this->client=new Visitor();	
				
		$this->toLog("Site initialized","event");
	}
	
//___________________________________________[ G E T T E R S ]___________________________________________|

	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
	
	public function getName(){
		return $this->name;
	}	
	public function getTemplate(){
		return $this->template;
	}
	
	public function getContent(){
		return $this->content;
	}
	public function setPath($a,$b,$p){
		$this->paths[$a][$b]=$p;
	}
	public function getPageRequest(){
		$var = new Variable('SESSION','page_request');
		if($var->getValue()){
			$page = $var->getValue();
			return $page;
		}
		return false;
	}
	
//___________________________________________[ S E T T E R S ]___________________________________________|

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			switch($property){
				case "name":
					$this->$property = $value;
				break;		
				case "paths":
					$this->$property = $value;
				break;					
			}
		}
	}
	
	public function setAcountSystem($as){
		$this->acountSystem=$as;
	}
	
	public function setDefaultContent($c){
		$this->default_content=$c;
	}
	
	public function setDefaultTemplate($t){
		$this->default_template=$t;
	}
	
	public function setDefaultPage($p){
		$this->default_page=$p;
	}
	
	public function setPageRequest($p){
		$var = new Variable('SESSION','page_request');
		if($var->setValue($p)){
			return true;
		}
		return false;
	}
	
	public function setName($s){
		$this->name=$s;
	}
	
	//defini le Template actuel par l'URL
	private function setTemplateFromGET(){
		$get = new Variable('GET','t');
		$templateName =$get->getValue();
		if($templateName!==false){
			$this->template= new Template($templateName); 
		}else{
			$this->template = new Template($this->default_template); 
		}	
	}
	private function setContentFromGET(){
		$get = new Variable('GET','c');
		$contentName =$get->getValue();
		if($contentName!==false){
			$this->content = new Content($contentName); 
		}else{
			$this->content = new Content($this->default_content); 
		}

	}
	//defini le Page actuel par l'URL
	private function setPageFromGET(){
		$get = new Variable('GET','p');
		$pageName =$get->getValue();
		if($pageName!==false){
			$this->page = new Page($pageName); 
			$this->setPageRequest($pageName);
		}else{
			$this->page = new Page($this->default_page); 
			$this->setPageRequest($this->default_page);
		}	
	}					



//____________________________________________[ A D D E R S ]____________________________________________|

	//ajout d'un composant au tableau map selon son type 
	public function add($component){
		$case = array($component,$component->ID);
		$mapIndex = 0;
		if(!array_key_exists($component->type,$this->map)){
			//si aucun composant de ce type n'est dans le tableau on creer une nouvelle colonne
			$this->map[$component->type]=array();
			array_push($this->map[$component->type],$case);
			$mapIndex = count($this->map[$component->type]);
		}else{
			array_push($this->map[$component->type],$case);
			$mapIndex = count($this->map[$component->type]);
		}
		//on renvoi son index dans le tableau
		return $mapIndex-1;
	}

	//ajout de chemins
	public function addPath($type,$index,$value){
		$this->paths[$type][$index]=$value;		

	}
	
//_________________________________________[ G E N E R A T O R S ]_______________________________________|

	//on incrémente le generator a chaque demande d'ID
	public function generateID(){
		$this->generator++;
		return $this->generator;
	}

//____________________________________________[ P A R S E R ]____________________________________________|

	
	//-----------ensemble de fonctions permettant de generer des chemins dans les balises html 
	//--genere des URL avec variables en GET
	public function parseGetURL($variable,$value){
		$url = $this->URL."index.php?".$variable."=".$value;
		return $url;
	}
	public function parseRequestURL($variable,$value){
		$url = $this->URL."request.php?".$variable."=".$value;
		return $url;
	}
	// il faudrait faire une fonction qui prend deux arrays en argument mais la flemme
	public function parseDoubleGetURL($variable1,$value1,$variable2,$value2){
		$url = $this->URL."index.php?".$variable1."=".$value1."&".$variable2."=".$value2;
		return $url;
	}
	
	public function parseTripleGetURL($variable1,$value1,$variable2,$value2,$variable3,$value3){
		$url = $this->URL."index.php?".$variable1."=".$value1."&".$variable2."=".$value2."&".$variable3."=".$value3;
		return $url;
	}
	//renvoi l'url de la Page souhaitée (marche potentielement en local et en ligne)
	public function parseURLFor($Page){
		$url=$this->parseGetURL("p",$Page);
		return $url;
	}
	public function RequestURL($Page){
		$url=$this->parseRequestURL("p",$Page);
		return $url;
	}
	//renvoi l'url de la Page souhaitée avec le Template souhaité (marche potentielement en local et en ligne)
	public function parseTemplateURLFor($Page,$Template){
		$url=$this->parseDoubleGetURL("p",$Page,"t",$Template);
		return $url;
	}
	public function parseTripleURLFor($Page,$Template,$Content){
		$url=$this->parseTripleGetURL("p",$Page,"t",$Template,'c',$Content);
		return $url;
	}
	
	//creer des balises script pour tout les fichier js contenus dans le dossier "js"
	public function getJsTag(){
		$this->toLog("generating Js Tags ...","process");
		$jsDir = "src/js";
		$output = "";
		$jsCount=0;
		if(file_exists($jsDir)){
			$dir = new Dir($jsDir);
			foreach ($dir->scan('js') as $file){
				$output.='	<script src="'.$file->getURL().'" type="text/javascript"></script>'."\n";
				$this->toLog("script Tag added for ".$file->getURL(),"event");
			}
		}else{
			$this->toLog("Js path does not exist : ".$jsDir,"error");
			return false;
		}
		return $output;			
	}
	//creer des balises <script> pour tout les fichiers js contenus dans les dossiers js des templates et pages
	public function parseScriptTags(){	
		$this->toLog("generating script Tags in Index.php ....","process");
		$output=$this->getJsTag().$this->template->getJsTag().$this->content->getJsTag();
		$this->toLog("script Tags parsed","event");
		return $output;
	}

	public function parsePageButton($page,$innerHTML,$class='bt'){
		$url=$this->parseTripleURLFor($page,$this->template->getName(),$this->content->getName());	
		$onclick='window.location.href = \''.$url.'\';';
		$output='<button class="'.$class.'" onclick="'.$onclick.'">'.$innerHTML.'</button>'."\n";
		return $output;
	}


//____________________________________________[ C R E A T O R S ]____________________________________________|

	public function createHgPageEditList(){
		$hgContent = new Dir($this->paths["S"]['hg'].'/content/');
		$hgPages = $hgContent->getDirs();

		$path = str_replace("http://","",$this->paths["C"]['hg']);
		$path = $this->paths["C"]['hg'];
		$content='<html><body>'."</br>"."\n";
		$content.="editables pages : </br>"."\n";
		$content.='<a href="'.$path.'?pages" target="_blank">HOTGLUE EDITOR</a>'."</br>"."\n";
		$content.="<ul>"."\n";
		 foreach($hgPages as $page){
			$content.='<li><a href="'.$path.'?'.$page.'/edit" target="_blank">'.$page.'</a></li>'."\n";
		 }
		 $content.="</ul>"."\n";
		 $content.="</body></html>"."\n";
		$file = new File('edit.html');
		$file->setContent($content);
		 if(!file_exists('edit.html')){
			$file->create();
		}else{
			$file->write($content,'over');
		}

	}

//____________________________________________[ C H E C K E R S ]____________________________________________|
	
	public function controlAccess($restrictedPages,$deviate){
		$page =$this->page->getName();
		$deviateURL = $this->content->getServerPath('dir').$deviate.'.php';
		foreach($restrictedPages as $p){
			if($page == $p){
				$this->setPageRequest($page);
				return  $deviateURL;
			}
		}
		return $this->page->getINC();
	}

	//on verifi si cela ne risque pas de perturber le fonctionnement du site
	public function isAllowed($component,$string,$updateLog){
		$component->blocked=false;
		$forbiddenNames=array('HEAD.php','FOOT.php','index.php','index.html','Templates','src','init.php','<?php','.git','.bash','.bat','.dll','.exe','rm*','.htaccess');;
		$check=0;
		for ($i=0;$i<count($forbiddenNames);$i++){
			if (strpos($string,$forbiddenNames[$i])==true) {		
				$check++;
			}
		}
		if($check==0){
			$component->blocked=false;
			if($updateLog){
				$component->Log('"'.$string.'" >>> Ok!','success');
			}
			return true;
		}else{
			$component->blocked=true;
			if($updateLog){
				$component->Log('"'.$string.'" >>> Not ok!','error');
			}else{
				return true;
			}
			return false;
		}
	}

//__________________________________________[ C L E A N E R S ]_____________________________________|


	//permet de securiser les channels utilisateurs
	public function cleanString($string,$type='html'){
		//$str = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
		$str="";
		switch($type){
			case 'html>':
				$str =  htmlentities($string, ENT_QUOTES, 'UTF-8');			
			break;
			case 'html<':
				$str =  $string;			
			break;
			case 'post':
				$str = $string;			
			break;
			case 'get':
				$str = $string;				
			break;
			case 'sql':
				$str = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
				$str = htmlentities($string, ENT_QUOTES, 'UTF-8');			
			break;
			default:
				$str = htmlentities($string, ENT_QUOTES, 'UTF-8');
			break;
		}
		return $str;
	}
}

//____________________________________________[ L O G ]____________________________________________|

	//creer le log 
	public function activateLog(){
		$logDir = new Dir('data/logs');
		$logDir->create();
		$this->addPath("S","Log",$this->ROOT.$logDir->getURL());
		$this->addPath("C","Log",$this->URL.$logDir->getURL());
		$this->Log = new Log();
		$this->logActivated=true;
	}
	//desactive le log , bloque l'ecriture 
	public function desactivateLog(){
		$this->logActivated=false;
	}
	
	
	//ajoute une alerte au log 
	public function addToLog($alert){
		if($this->logActivated){
			if($this->logfilter =='bypass'||$this->logfilter ==NULL){
				$this->Log->addAlert($alert);
				$this->Log->updateFile();
			}else if($alert->getType() == $this->logfilter){
				$this->Log->addAlert($alert);
				$this->Log->updateFile();			
			}
		}
		return false;
	}
	public function filterLog($f){
		$this->logfilter=$f;
	}
	
	public function toLog($content,$alertType='event'){
		//on ajoute le type du Component au debut de l'alert. ex : file->Log("erreur")  affiche dans le log : "_[file]-->erreur"
		//ca le rend plus cool aussi 
		$coolContent = "_[<b>Site</b>]-->".$content;
		$oAlert = new Alert($coolContent,$alertType,$this->ID,$this->type."_alert");
		//on ajoute l'alerte au log du site
		$this->addToLog($oAlert);
	}
?>

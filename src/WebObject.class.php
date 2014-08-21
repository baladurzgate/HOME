<?php //--WEBOCJECT--?>
<?php
class WebObject extends Component {
	private $name;
	private $webObjectType;
	private $serverPaths=array();
	private $clientPaths=array();

    public function __construct($name,$type,$updateLog=true) {
		$this->name=$name;
		$this->webObjectType=$type;
		parent::__construct($type);
		if($updateLog){
			$this->Log("new ".$type,"event");
		}

	}
	public function initPaths($updateLog=true){
	
		$type = $this->webObjectType;
		$name=$this->getName();
		//chemins navigateur
		$this->clientPaths['dir']=$this->S->paths["C"][$type."s"].$name."/";
		$this->clientPaths['php']=$this->clientPaths['dir']."home.php";		
		$this->clientPaths['js']=$this->clientPaths['dir']."js";
		
		//chemins serveur
		$this->serverPaths['dir']=$this->S->paths["S"][$type."s"].$name."/";
		$this->serverPaths['php']=$this->serverPaths['dir']."home.php";		
		$this->serverPaths['js']=$type.'s/'.$name."/js";
		if($updateLog){
			$this->Log($this->clientPaths['dir'],"info");
			$this->Log($this->serverPaths['dir'],"info");
		}
		
	}

	//getters
	public function getServerPath($sp){
		if(isset($this->serverPaths[$sp])){
			$this->Log($this->serverPaths[$sp],"info");
			return $this->serverPaths[$sp];
		}
		return false;
	}
	public function getClientPath($cp){
		if(isset($this->clientPaths[$cp])){
			$this->Log($this->clientPaths[$cp],"info");
			return $this->clientPaths[$cp];
		}
		return false;	
	}	
	public function getName(){
		return $this->name;	
	}	
	public function getJsTag(){
		$this->Log("generating Js Tags...","process");
		$jsDir = $this->getServerPath('js');
		$output = "";
		if(file_exists($jsDir)){
			$dir = new Dir($jsDir);
			$jsFiles=$dir->scan('js');
			if($jsFiles!==false){
				foreach ($jsFiles as $file){
					$output.='	<script src="'.$file->getURL().'" type="text/javascript"></script>'."\n";
					$this->Log("script Tag added for ".$file->getURL(),"event");
				}
			}
		}else{
			$this->Log("Js path does not exist : ".$jsDir,"error");
			return false;
		}
		return $output;			
	}
}
?>

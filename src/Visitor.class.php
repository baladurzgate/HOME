<?php
class Visitor extends Component{

	//variables
	private $id;
	private $browser;
	
	//constructeur
    public function __construct($updateLog=true){
		parent::__construct("client");
		
		// recuperation des infos sur le navigateur du client
		$this->browser = $_SERVER['HTTP_USER_AGENT'];
		
		//log
		if($updateLog){
			$this->Log("new client ","event");
			$this->Log("browser :".$this->browser,"info");
		}
    }
	
	//getter
	public function getBrowser(){
		$browser =$_SERVER['HTTP_USER_AGENT'];
		return ($browser);
	}
	
	public function getSessionVar($v){
		if(isset($_SESSION[$v])){
			return $_SESSION[$v];
		}else{
			return "";
		}
	}
	
}
?>
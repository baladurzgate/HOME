<!--VISITOR-->
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
		
		//on demarre une session
		session_save_path($this->S->paths['S']['sessions']);
		if(session_start()){
			$_SESSION['welcome']="hello!";
			$this->Log("session started--->".$_SESSION['welcome'],"event");
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

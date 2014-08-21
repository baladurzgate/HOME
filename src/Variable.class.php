<?php
class Variable extends Component{
	
	//variables
	private $value;
	private $name;
	private $varType;
	
	//consctructeur
    public function __construct($type="POST",$name){
		parent::__construct('variable');
		$this->name = $name;
		$this->varType = $type;
	}
	public function getValue(){
		$type=$this->varType;
		$name=$this->name;
		switch($type){
			case "POST":
				if(isset($_POST[$name])){
					if($_POST[$name]!=NULL){
						$this->value=$this->S->cleanString($_POST[$name],'post');
						return $this->value;
					}else{
						return false;
					}
					return false;
				}
			break;
			case "GET":
				if(isset($_GET[$name])){
					if($_GET[$name]!=NULL){
						$this->value=$this->S->cleanString($_GET[$name],'get');
						return $this->value;
					}else{
						return false;
					}
					return false;
				}
			break;		
			case "SESSION": 
				$sessions=new Session();
				$this->value=$sessions->getValue($name);
				return $this->S->cleanString($this->value);
			break;	
			case "COOKIE":
				if(isset($_COOKIE[$name])){
					if($_COOKIE[$name]!=NULL){
						$this->value=$this->S->cleanString($_COOKIE[$name],'cookie');
						return $this->value;
					}else{
						return false;
					}
					return false;
				}			
			break;			
		}
		return false;	
	}
	public function setValue($v){
		$type=$this->varType;
		$name=$this->name;
		switch($type){
			case "SESSION":
				$sessions=new Session();
				$sessions->setValue($name,$v);
				return true;
			break;		
			case "COOKIE":
				$expire = 365*24*3600; 
				setcookie($name,$v,time()+$expire);  
			break;		
		}
		return false;	
	}
}
?>
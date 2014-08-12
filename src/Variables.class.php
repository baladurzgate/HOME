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
				if(isset($_SESSION[$name])){
					if($_SESSION[$name]!=NULL){
						$this->value=$this->S->cleanString($_SESSION[$name],'session');
						return $this->value;
					}else{
						return false;
					}
					return false;
				}
			break;	
			case "COOKIE":
				//WIP
			break;			
		}
		return false;	
	}
	public function setValue($v){
		$type=$this->varType;
		$name=$this->name;
		switch($type){
			case "SESSION":
				$_SESSION[$name]=$this->S->cleanString($v,'session');
				return true;
			break;		
			case "COOKIE":
				//COOKIE;
			break;		
		}
		return false;	
	}
}
?>

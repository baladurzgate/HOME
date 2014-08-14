<?php
class Session extends Component{
	
	//variables
	private $name;
	private $values;
	private $sessionDir;
	private $sessionFile;

	
	//consctructeur
    public function __construct(){
		parent::__construct('session');
		$this->name = sha1($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
		$this->values = array();
		$url = $this->S->paths['S']['sessions'];
		$fileURL=$url.'/'.$this->name;
		if(file_exists($this->S->paths['S']['data'])){
			$this->sessionDir=new Dir($url);
			$this->sessionFile = new File($fileURL);
			if(!file_exists($url)){
				$this->sessionDir->create();
				$this->sessionFile->create();
			}else{
				if(!file_exists($fileURL)){
					$this->sessionFile->create();
				}
			}
		}
	}
	public function getValue($n){
		$values = $this->getValues();
		if(isset($values[$n])){
			return $values[$n];
		}	
		return false;
	}
	public function setValue($n,$v){
		$values = $this->getValues();
		$values[$n]=$v;
		$string=arrayToString($values,"\n","=");
		$this->sessionFile->setContent($string);
		$this->sessionFile->write($string,"over");
		
	}
	
	public function getValues(){
		$str=$this->sessionFile->read();
		$array=array();
		if($str!==false){
			$array=stringToArray($str,"\n",'=');			
		}
		return $array;
	}
	public function delete(){
		$this->sessionFile->delete();
	}
}
?>

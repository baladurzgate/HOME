<?php
class UserMap extends Component{
	
	//variables
	private $name;
	private $values;
	private $sessionDir;


	
	//consctructeur
    public function __construct(){
		parent::__construct('usermap');
		$this->values = array();
		$url = $this->S->paths['S']['sessions'];
		if(file_exists($this->S->paths['S']['data'])){
			if(file_exists($url)){
				$this->sessionDir=new Dir($url);
			}
		}
	}
	public function getMap(){
		return $this->extractMap();
	}
	private function extractMap(){
		$output=array();
		$arrayOfFiles=$this->sessionDir->scan();
		foreach($arrayOfFiles as $f){
			$userData=stringToArray($f->read(),"\n","=");
			array_push($output,$userData);
		}
		return $output;
	}
	
	public function appendMap(){
		$output="";
		foreach($this->extractMap() as $f){
			$output.=arrayToString($f,"\n","=").'*';
		}
		$output = substr($output, 0, -1);
		echo $output;
	}

}
?>
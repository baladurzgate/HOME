<?php //--XML--?>
<?php
class XML extends File {
	//variables 
	private $DOM;
	
	//consctructeur
    public function __construct($url,$updateLog=true){
		parent::__construct($url,$updateLog);
		$this->DOM = new DOMDocument('1.0', 'utf-8');
		$this->DOM->formatOutput = true;
		if($updateLog){
			$this->Log("new XML : ".$this->url,"event");
		}
    }
	public function setDOM(&$dom){
		$this->DOM=$dom;		
	}
	public function readDOM(){
		$this->Log("reading DOM...","process");
		$str =$this->read();
		$this->DOM->loadXML($str);
		return $this->DOM;
	}
	public function getValue($tagName){
		$tag = $this->DOM->getElementsByTagName($tagName);
		return $tag->item(0)->nodeValue;
	}
	public function checkValue($tagName,$value){
		$tag = $this->DOM->getElementsByTagName($tagName);
		if($tag->item(0)->nodeValue==$value){
			return true;
		}
		foreach ($tags as $t) {
			echo $t->nodeValue, PHP_EOL;
		}
		return false;
	}
	public function createXML(){
		$this->Log("creatind XML file...","process");
		$this->DOM->formatOutput = true;
		$content = $this->DOM->saveXML($this->DOM,LIBXML_NOEMPTYTAG);
		$this->setContent($content);
		$this->create();
	}
	public function writeXML(){
		$this->Log("writing XML file...","process");
		$this->DOM->formatOutput = true;
		$content = $this->DOM->saveXML($this->DOM,LIBXML_NOEMPTYTAG);
		$this->setContent($content);
		$this->write($content,'over',true);
	}
	//getters
	public function getDOM(){
		return $this->DOM;
	}
	

}
?>
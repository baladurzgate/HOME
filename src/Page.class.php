<?php
//<!-- PAGE -->
class Page extends WebObject {
	private $access;
    public function __construct($name,$access='all',$updateLog=true) {
		parent::__construct($name,"page");
		$this->access=$access;
	}
	
	public function getURL(){
		$name = $this->getName();
		$contentPath = $this->S->content->getClientPath('dir');
		return $contentPath.$name.".php";
	}
	public function getINC(){
		$name = $this->getName();
		$contentPath = $this->S->content->getServerPath('dir');
		return $contentPath.$name.".php";
	}
	public function getAccess(){
		return $this->access();
	}
		
	public function append(){
		$name = $this->getName();
		$contentPath = $this->S->content->getServerPath('dir');
		include $contentPath.$name.".php";;
	}
}
?>

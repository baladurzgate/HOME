<?php
//<!--TEMPLATE-->
class Template extends WebObject {
    public function __construct($name,$updateLog=true) {
		parent::__construct($name,"template");
		$this->initPaths();
	}
	public function append(){
		include ($this->getServerPath('php'));
	}
}
?>

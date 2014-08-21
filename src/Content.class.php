<?php //--CONTENT--?>
<?php
class Content extends WebObject {
    public function __construct($name,$updateLog=true) {
		parent::__construct($name,"content");
		$this->initPaths();

	}
}
?>

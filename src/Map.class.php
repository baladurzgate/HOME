<?php
//<!-- MAP -->
class Map extends Component {
	private $name;
	private $map = array();
    public function __construct($name) {
		parent::__construct("cache");
		$this->name=$name;
	}
	
	public function set($name,$thing){
		$this->map[$name]=$thing;
	}
	public function get($name){
		if(isser($this->map[$name])){
			return $this->map[$name];
		}
	}

	public function getName(){
		return $this->name;
	}
	public function getArray(){
		return $this->map;
	}
	public function getLength(){
		return count($this->map);
	}

}
?>

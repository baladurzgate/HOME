<?php
//<!--FILTER-->
class Filter extends Component {
	private $sign;
	private $name;
	private $value;
	
	//construction 
    function __construct($name,$sign="==",$value){
		parent::__construct('filter');
		$this->sign=$sign;
		$this->name=$name;
		$this->value=$value;
    }
	
	public function getSign(){
		return $this->sign;
	}
	
	public function compare($array){
		if(isset($array[$this->name])){
			$a  = $array[$this->name];
			switch ($this->sign){
				case '==':
					return($a==$this->value);
				break;
				case '<=':
					return($a<=$this->value);
				break;
				case '<':
					return($a<$this->value);						
				break;
				case '>=':
					return($a>=$this->value);						
				break;
				case '>':
					return($a>$this->value);						
				break;
				case 'between':
					return($a>$this->value[0]&&$a<$this->value[1]);						
				break;
				case 'contain':
					return(strpos($a,$this->value)!==false);						
				break;
				case 'lack':
					return(strpos($a,$this->value)==false);						
				break;
			}
		}
		return false;
	}
	

}
?>

<?php
//<!--USERACOUNT-->
class UserAcount extends Post{

	private $acountSystem;
	
	//constructor
    public function __construct($acountSystem=NULL){
		parent::__construct("acount",$acountSystem);
		$this->changeType('acount');
		$this->accountSystem = $acountSystem;
		$this->init();
		$this->Log("new useracount","event");	

    }
	public function init(){

	}

	public function exist(){
		if($this->acountSystem!==NULL){		
			return $this->accountSystem->acountAllreadyExist($this);
		}else{
			return false;
		}
	}
}
?>

<!-- ALERT -->
<?php
class Alert extends Component{

	//variables 
	private $alertType;
	private $content;
	private $timestamp;
	private $id;
	private $style;
	private $classCSS;

	//constructeur
    public function __construct($content,$alertType='event',$id=0,$classCSS="standarAlert"){
		parent::__construct("alert");
		$this->timestamp = date("F j, Y, g:i a");  
		$this->alertType = $alertType;  
		$this->content = $content;  
		$this->id = $id;  
		$this->classCSS = $classCSS;  
    }
	
	//GETTER
	public function getContent(){
		return $this->content;
	}
	public function getType(){
		return $this->alertType;
	}	
	//SETTER
	public function setContent($c){
		$this->content=$c;
	}
	
	//affichage de l'alerte
	public function display(){
		$output="";
		switch($this->alertType){
			case 'info':
				$this->style="color:black;";
			break;
			case 'event':
				$this->style="color:blue;";
			break;
			case 'process':
				$this->style="color:purple;";
			break;
			case 'error':
				$this->style="color:red;";
			break;
			case 'success':
				$this->style="color:green;";
			break;
		}
		$output ='<span class="alertTimestamp"><i>'.$this->timestamp.'</i></span><span id="'.$this->id.'" class="'.$this->classCSS.'" style="'.$this->style.'" >'.$this->content.'</span>'."\n";
		return $output;
		
	}

}
?>

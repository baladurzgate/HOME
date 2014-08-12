<?php
	/*
											* * * * * * * * * * * *
											*                     * 
											*   F E E D B A C K   *
											*                     *
											* * * * * * * * * * * *
															
	Gestion des messages destinés à l'utilisateur										
	*/
class Feedback extends Component{

	//variabmessageses
	private $messages;

	//construction
    function __construct() {
		parent::__construct("feedback");
		$this->messages=array();
    }

	//ajoute un message 
	public function addMessage($m,$source){
		$this->messages[$source]=$this->S->cleanString($m);
	}
	
	//getters
	public function getMessages(){
		return $this->messages;
	}
	//parseurs
	public function parseFeedbackTags(){
		$output='</br>'."\n";
		$output.='<div class="feedback">'."\n";
		foreach($this->messages as $s => $m){
			$output.=$this->parseMessageTag($s);
		}
		$output.='</div></br>'."\n";
		return $output;
	}
	public function parseMessageTag($s){
		$output="";
		if(isset($this->messages[$s])){
			$output='<span class="feedback-source">['.$s.'] :  </span><span class="feedback-message">'.$this->messages[$s].'</span></br>'."\n";
		}
		return $output;
	}
	
	public function append(){
		$templateUrl = $this->S->template->getServerPath('dir').'/'.'feedback.php';
		if(file_exists($templateUrl)){
			$messages = $this->messages;
			include($templateUrl);
		}else{	
			echo $this->parseFeedbackTags();
		}
	}
	
	public function merge($feedback){
		$messages = $feedback->getMessages();
		foreach($messages as $s => $m){
			$this->messages[$s] = $m;
		}
	}
	public function erase(){
		$this->messages=array();
	}
}
?>

<?php //--CHANNEL--?>
<?php
class Channel extends Component {

	//variables 
	private $attr = array();
	private $label;
	private $mandatory;
	private $table;
	private $feedback;
	private $access;
	private $defaultValue;
	
	//construct
    public function __construct($name='newchannel',$Attributes,$label=NULL,$updateLog=true) {
		parent::__construct("channel");
		$this->feedback = new Feedback($name);
		$this->attr['name']=$name;
		$this->access='form';
		if(is_array($Attributes)!==false){
			foreach ($Attributes as $n=>$v){
				$this->attr[$n]=$this->S->cleanString($v);
			}
		}
		if(!isset($this->attr['type'])){
			$this->attr['type']='text';
		}
		if(isset($this->attr['access'])){
			$this->access=$this->attr['access'];
		}
		if(!isset($this->attr['defaultvalue'])){
			$this->defaultValue="...";
		}else{
			$this->defaultValue=$this->attr['defaultvalue'];
		}
		if(isset($this->attr['mandatory'])){
			if($this->attr['mandatory']=='true'){
				$this->mandatory=true;
			}else{
				$this->mandatory=false;
			}
		}else{
			$this->mandatory=false;
		}
		if($label==NULL){
			if(isset($this->attr['name'])){
				$this->label=$this->attr['name'];
			}
		}else{
			$this->label=$this->S->cleanString($label);
		}
		if($updateLog){
			$this->Log("new channel :'".$this->attr['name']."' of type :".$this->attr['type'],"event");
		}
		if(!isset($this->attr['minChar'])){
			$this->attr['minChar']=0;
		}
		if(!isset($this->attr['maxChar'])){
			$this->attr['maxChar']=100;
		}
		
		if(!isset($this->attr['treatment'])){
			$this->attr['treatment']='normal';
		}
	}

	//getters
	public function getAttributes(){
		return $this->attr;
	}
	private function getAttribute($attr){
		if(isset($this->attr[$attr])){
			return $this->attr[$attr];
		}else{
			$this->Log($attr." unKnown attribute!","error");
		}
		return false;
		
	}
	public function getName(){
		return $this->getAttribute('name');
	}
	public function getChannelType(){
		return $this->getAttribute('type');
	}
	public function getLabel(){
		return $this->label;
	}	
	public function getMaxChar(){
		return $this->getAttribute('maxChar');
	}
	public function getMinChar(){
		return $this->getAttribute('minChar');
	}
	public function getAccess(){
		return $this->getAttribute('access');
	}
	public function getFeedback(){
		return $this->feedback;
	}
	public function getTreatment(){
		return $this->getAttribute('treatment');
	}
	public function isMandatory(){
		return $this->mandatory;
	}
	
	//setter
	public function setLabel($text){
		$this->label=$text;
	}	
	public function setMandatory($bool){
		$this->mandatory=$bool;
	}
	public function setAttribute($attr,$v){
		if(isset($this->attr[$attr])){
			$this->attr[$attr]=$this->S->cleanString($v);
		}
		return false;
	}
	public function setMaxChar($int){
		$this->setAttribute('maxChar',$int);
	}
	public function setMinChar($int){
		$this->setAttribute('minChar',$int);
	}
	public function setTable($t){
		$this->table=$t;
	}		

	//verifications des valeurs venant du client et creation de messages de feedback
	public function checkValue($value){
		$feedback = $this->feedback;
		$type=$this->getChannelType();
		$access=$this->access;
		if($type==NULL||$type==""){
			$type="text";
		}
		switch($type){
			case "text":
				if($this->checkString($value)!==false){
					$this->switchFeedback();
					return true;
				}
			break;
			case "int":
				return true;
			break;	
			case "title":
				if($this->checkString($value)!==false){
					$this->switchFeedback();
					return true;
				}
			break;
			case "article":
				if($this->checkString($value)!==false){
					$this->switchFeedback();
					return true;
				}
			break;
			case "mp":
				if($this->checkString($value)!==false){
					$this->switchFeedback();
					return true;
				}
			break;
			case "nickname":
				if($this->checkString($value)!==false){
					$this->switchFeedback();
					return true;
				}
			break;
			case "email":
				if($this->checkString($value)!==false){
					$this->switchFeedback();				
					return true;
				}
			break;
			case "checkbox":
				$this->switchFeedback();
				return true;
			break;
			case "submit":
				$this->switchFeedback();
				return true;
			break;
			case "hidden":
				if($this->checkString($value)!==false){
					$this->switchFeedback();
					return true;
				}
			break;
			case "status":
				if($this->checkString($value)!==false){
					$this->switchFeedback();
					return true;
				}
			break;
			case "author":
				if($this->checkString($value)!==false){
					$this->switchFeedback();
					return true;
				}
			case "call":
				if($this->checkString($value)!==false){
					$this->switchFeedback();
					return true;
				}
			break;
			default:
				if($value==NULL){$value="";}
				if($this->checkString($value)!==false){
					$this->switchFeedback();
					return true;
				}			
			break;
		}
		return false;
	}
	public function switchFeedback(){
		$access=$this->access;
		$feedback = $this->feedback;
		switch($access){
			case "archives":
					$feedback->erase();
			break;
			case "none":
					$feedback->erase();
			break;
			case "user":

			break;
			default:
			
			break;
		
		}	
	}
	public function checkString($string){
		$max = $this->getMaxChar();
		$min = $this->getMinChar();
		$len = strlen($string);
		$feedback = $this->feedback;
		$errorCount=0;
		if($string==""||$string=NULL){
			if($this->mandatory==true){
				$errorCount++;
				$feedback->addMessage("champ vide",$this->getName());
				return false;
			}else{
				return true;
			}
			$this->Log("empty feild","error");
		}else if($len>$max){
			$feedback->addMessage("ce champ ne peut comporter que ".$max." caracteres maximum",$this->getName());
			$errorCount++;
			$this->Log(">charMax","error");
		}else if($len<=$min){
			$feedback->addMessage("ce champ doit comporter au moins ".($min+1)." caracteres ",$this->getName());
			$errorCount++;
			$this->Log("<charMin","error");
		}else{
			$this->Log("channel valid","success");
		}		
		if($errorCount==0){	
			return true;
		}
		return false;
	}
	//traitement des valeurs destinées à atterir sur le serveur
	public function toServer($value){
		$output="no values";
		if($this->getChannelType()){
			switch($this->getChannelType()){
				case "text":
					switch($this->getTreatment()){
						case 'normal':
							$output=$this->S->cleanString($value,'html>');
						break;
						case 'crypted':
							$output=sha1($this->S->cleanString($value,'html>'));
						break;
					}
				break;
				case "int":
					switch($this->getTreatment()){
						case 'normal':
							$output=intval($this->S->cleanString($value,'html>'));
						break;
						case 'crypted':
							$output=sha1($this->S->cleanString($value,'html>'));
						break;
					}
				break;	
				case "title":
					switch($this->getTreatment()){
						case 'normal':
							$output=$this->S->cleanString($value,'html>');
						break;
						case 'crypted':
							$output=sha1($this->S->cleanString($value,'html>'));
						break;
					}
				break;
				case "article":
					switch($this->getTreatment()){
						case 'normal':
							$output=$this->S->cleanString($value,'html>');
						break;
						case 'crypted':
							$output=sha1($this->S->cleanString($value,'html>'));
						break;
					}
				break;
				case "mp":
					switch($this->getTreatment()){
						case 'normal':
							$output=$this->S->cleanString($value,'html>');
						break;
						case 'crypted':
							$output=sha1($this->S->cleanString($value,'html>'));
						break;
					}
				break;
				case "nickname":
					switch($this->getTreatment()){
						case 'normal':
							$output=$this->S->cleanString($value,'html>');
						break;
						case 'crypted':
							$output=sha1($this->S->cleanString($value,'html>'));
						break;
					}
				break;
				case "email":
					$output=sha1($this->S->cleanString($value,'html>'));
				break;
				case "checkbox":
					switch($this->getTreatment()){
						case 'normal':
							$output=$this->S->cleanString($value,'html>');
						break;
						case 'crypted':
							$output=sha1($this->S->cleanString($value,'html>'));
						break;
					}
				break;
				case "submit":
					switch($this->getTreatment()){
						case 'normal':
							$output=$this->S->cleanString($value,'html>');
						break;
						case 'crypted':
							$output=sha1($this->S->cleanString($value,'html>'));
						break;
					}
				break;
				case "hidden":
					switch($this->getTreatment()){
						case 'normal':
							$output=$this->S->cleanString($value,'html>');
						break;
						case 'crypted':
							$output=sha1($this->S->cleanString($value,'html>'));
						break;
					}
				break;
				case "status":
					switch($this->getTreatment()){
						case 'normal':
							$output=$this->S->cleanString($value,'html>');
						break;
						case 'crypted':
							$output=sha1($this->S->cleanString($value,'html>'));
						break;
					}
				break;
				case "author":
					switch($this->getTreatment()){
						case 'normal':
							$output=$this->S->cleanString($value,'html>');
						break;
						case 'crypted':
							$output=sha1($this->S->cleanString($value,'html>'));
						break;
					}
				break;
				case "call":
					switch($this->getTreatment()){
						case 'normal':
							$output=$this->S->cleanString($value,'html>');
						break;
						case 'crypted':
							$output=sha1($this->S->cleanString($value,'html>'));
						break;
					}
				break;
				default:
					switch($this->getTreatment()){
						case 'normal':
							$output=$this->S->cleanString($value,'html>');
						break;
						case 'crypted':
							$output=sha1($this->S->cleanString($value,'html>'));
						break;
					}			
				break;

			}
		}		
		return $output;
	}
	//traitement des valeurs destinées à être affichées sur la page du client
	public function toClient($value){
		$output="";
		if(isset($this->attr['type'])){
			switch($this->attr['type']){
				case "text":
					$clean=$this->S->cleanString($value,'html<');
					$output=str_replace("\n",'</br>',$clean);
				break;
				case "int":
					$output=$this->S->cleanString($value,'html<');
				break;	
				case "title":
					$output=$this->S->cleanString($value,'html<');
				break;
				case "article":
					$output=$this->S->cleanString($value,'html<');
				break;
				case "mp":
					$output=($value);
				break;
				case "nickname":
					$output=$this->S->cleanString($value,'html<');
				break;
				case "email":
					$output=($value);
				break;
				case "checkbox":
					$output=$this->S->cleanString($value,'html<');
				break;
				case "submit":
					$output=$this->S->cleanString($value,'html<');
				break;
				case "hidden":
					$output=$this->S->cleanString($value,'html<');
				break;
				case "status":
					$output=$this->S->cleanString($value,'html<');
				break;
				case "author":
					$output=$this->S->cleanString($value,'html<');
				break;
				case "call":
					//channel particulier , contiens des noms de post d'une autre table sÃ©parÃ©s par une virgule (ex: post_746876,post_8756876...)
					//l'output renvoi un array de posts qui sera ensuite traitÃ©e au moment de leur affichage (voir Post->append)
					$output=explode(",",$this->S->cleanString($value,'html<'));
				break;
				default:
					$output=$this->S->cleanString($value,'html<');		
				break;
			}
		}		
		return $output;
	}
	//generator
	public function parseTag(){
		$output="";
		$Attributes="non channels";
		$tag = "input";
		$defaultValue ="";
		$max = $this->getMaxChar();
		$min = $this->getMinChar();
		if($this->getChannelType()){
			switch($this->getChannelType()){
				case "text":
					$Attributes='type="text" cols=72 rows=6 wrap="hard" maxlength='.$max.' ';
					$tag="textarea";
					$defaultValue="text here please...";
				break;	
				case "int":
					$Attributes='type="number"';
				break;	
				case "title":
					$Attributes='type="text" size=20';
				break;
				case "article":
					$Attributes='type="text" size=200';
					$tag="textarea";
				break;
				case "mp":
					$Attributes='type="password" size=40 maxlength="'.$max.'"';
				break;
				case "nickname":
					$Attributes='type="text" size=40 maxlength="'.$max.'"';
				break;
				case "email":
					$Attributes='type="mail" size=40 maxlength="'.$max.'"';
				break;
				case "checkbox":
					$Attributes='type="checkbox" size=100';
				break;
				case "submit":
					$Attributes='type="submit"';
				break;
				case "hidden":
					$Attributes='type="hidden"';
				break;
				case "status":
					$Attributes='type="hidden"';
				break;
				case "author":
					$Attributes='type="hidden"';
				break;
				case "call":
					$Attributes='type="hidden"';
				break;
				default:
					$Attributes='type="text"';		
				break;
			}
			$l='<label for="channel-'.$this->attr['name'].'">'.$this->label.'</label>';
			$i="";
			if($tag=='input'){
				$i='<'.$tag.' '.$Attributes.' id="channel-'.$this->attr['name'].'" name="'.$this->attr['name'].'" class="channel-'.$this->attr['type'].'" value="'.$defaultValue.'">';
				$output=$i;
			}else{
				$i='<'.$tag.' '.$Attributes.' id="channel-'.$this->attr['name'].'" name="'.$this->attr['name'].'" class="channel-'.$this->attr['type'].'">'.$defaultValue.'</'.$tag.'>';
				$output=$i;
			}
		}
		return $output;
	}
}
?>

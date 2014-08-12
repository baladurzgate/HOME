<?php
	/*
										* * * * * * * * 
										*             *
										*   P O S T   *
										*             *
										* * * * * * * *
	*/
class Post extends Component {

	//variables 
	private $id;
	private $serial;
	private $values = array();
	private $table;
	private $postType;

	
	//consctructeur
    public function __construct($type="post",$table=NULL,$updateLog=true){
		parent::__construct($type);
		$this->postType = $type;
		$this->serial ="000000";
		if($updateLog){
			$this->Log("new post","event");
		}
		if($table!=NULL){
			$this->table=$table;
		}
    }
	
	//Manipulation des valeurs 
	
	//GET rend une valeur preparꥍ
	public function getSerial(){
		return $this->serial;
	}
	public function getPostType(){
		return $this->postType;
	}
	public function getValue($v){
		if(isset($this->values[$v])){
			$channels=$this->table->getChannelMap();
			return $channels[$v]->toClient($this->values[$v]);
		}
		return false;
	}
	public function getValues(){
		return $this->values;
	}
	public function setSerial($n){
		$this->serial=$n;
	}
	//SET passe une valeur traitꥠau prꢬable 
	public function setValue($i,$v){
		if($this->S->isAllowed($this,$v,true)){
			$channels=$this->table->getChannelMap();
			$this->Log("set value ".$i."...","info");
			$this->values[$i]=$channels[$i]->toServer($v);
		}
	}
	public function setValues($v){
		$this->Log("set values...","process");
		if(count($v)>1){
			foreach($v as $i => $valeur){
				$this->setValue($i,$valeur);
			}
		}
	}
	//COPY passe une valeur telle qu'elle
	public function copyValue($i,$v){
			$this->values[$i]=$v;
	}
	public function copyValues($v){
		$this->Log("copy values...","process");
		foreach($values as $i => $v){
			$this->copyValues($i,$v);
		}
	}
	public function setTable($t){
		$this->table=$t;
	}

	public function display($values){
		$output="";
		if(isset($values)&&count($values)>0){
			$type=$this->postType;
			$output='</br>'."\n";
			$output.='<div id="'.$this->values['id'].'" class="'.$this->table->getName().'-post" style="font-size:8; font-family:\'courrier\'">'."\n";
			$output.="\t".'<TABLE  BORDER="1">'."\n";
			$output.="\t".'<CAPTION>'.$type.'</CAPTION>'."\n";
			foreach($values as $name => $value){
				$output.="\t"."\t".'<TR align="left">'."\n";
				$output.="\t"."\t"."\t".'<TH>'.$name.'</TH>'."\n";
				$output.="\t"."\t"."\t".'<TH>'.$value.'</TH>'."\n";
				$output.="\t"."\t".'</TR>'."\n";;
			}
			$output.="\t".'</TABLE>'."\n";
			$output.='</div>'."\n";
			$output.='</br>'."\n";
		}
		return $output;
	}
	
	//append
	public function append(){
		$template = $this->table->getTemplates('post');
		$channels=$this->table->getChannelMap();
		$type=$this->postType;
		$values=$this->values;
		$serial=$this->getSerial();
		$outputs=array();
		foreach($channels as $n=>$i){
			$access = $i->getAccess();
			if($access){
				if(strpos($access,'archives')!==false){
					$outputs[$n]=$channels[$n]->toClient($values[$n]);
				}
			}
		}
		if(file_exists($template)){
			include($template);
		}else{
			echo $this->display($output);
		}
	
	}

	

}
?>

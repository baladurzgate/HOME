<?php
//<!--HgObject-->
class HgObject extends Component {

	//variables 
	private $name;
	private $objectType;
	private $str;
	private $data=array();
	private $page;
	
	
	//consctructeur
    public function __construct($file,$page){
		parent::__construct('hgobject');
		$this->page=$page;
		$this->str=$file->read();
		$this->name=$file->extractName();
		$this->objectType=$this->extractType($this->str);
		$this->init($this->str);
    }
	public function init($str){
		$type=$this->extractType($str);
		$data=array();
		switch($type){
			case 'text':
				$text=NULL;
				$isolateText = explode("\n\n",$str);
				if(count($isolateText)==2){
					$text = $isolateText[1];
					$dataBlock = $isolateText[0];
				}else{
					$dataBlock = $str;
				}
				$data = $this->extractData($dataBlock );
				if($text!=NULL){
					$data['text']=$isolateText[1];
				}
			break;
			case 'image':
				$data = $this->extractData($str);
			break;
			case 'webvideo':
				$data = $this->extractData($str);
			break;
		}		
		$this->data=$data;
		return $data ;
	}
	public function parseHTML(){
		$div='<div id="'.$this->name.'" style="'.$this->parseCSS().'">'."\n".$this->parseContent().'</div>'."\n";
		if(isset($this->data['link'])){
			$link = $this->data['link']['val'];
			$output ='<a href="'.$link.'">'.$div.'</a>';
		}else{
			$output =$div;
		}
		return $output;
	}
	
	public function append(){
		echo $this->parseHTML();
	}
	
	public function parseContent(){
		$data=$this->data;
		$content="";
		switch($this->objectType){
			case 'text':
				if(isset($data['text'])){
					$content=$data['text'];
				}
			break;
			case 'image':
				$img =array();
				$rimg = array();
				$content='';
				foreach($data as $n => $d){
					if($d['type']=='image'){
						switch($n){
							case 'file':
								$img['src']=$this->page->getPath('I','shared').$d['val'];
							break;							
							case 'resized-file':
								$rimg['src']=$this->page->getPath('I','shared').$d['val'];
							break;			
						}
					}
					if($d['type']=='object'){
						switch($n){
							case 'width':
								$img[$n]=$d['val'];
								$rimg[$n]=$d['val'];
							break;	
							case 'height':
								$img[$n]=$d['val'];
								$rimg[$n]=$d['val'];
							break;												
						}
					}
				}
				$attr = $img;
				if(isset($data['resized-file'])){
					$attr=$rimg;
				}
				$imageAttr="";
				foreach($attr as $n => $v){
					$imageAttr.=$n.'="'.$v.'" ';
				}
				$content = '<img '.$imageAttr.'>'."\n";
			break;
			case 'webvideo':
				$video = array();
				$attributes="";
				foreach($data as $n => $d){
					if($d['type']=='webvideo'){
						switch($n){
							case 'provider':
								print_r($data['provider']);		
								echo '</br>';
								switch($d['val']){
									case 'youtube':
										$video['server']="http://www.youtube.com/v/";
									break;
									case  'vimeo':
										$video['server']="";
									break;
								}
							break;	
							case 'id':
								$video['id']=$d['val'];
							break;												
						}
					}
					if($d['type']=='object'){
						switch($n){
							case 'width':
								$attributes.=$n.'="'.$d['val'].'"';
							break;	
							case 'height':
								$attributes.=$n.'="'.$d['val'].'"';
							break;												
						}
					}
				}
				
				$src="";
				if(isset($video['server'])&&isset($video['id'])){
					$src=$video['server'].$video['id'];
				}
				$content='<embed '.$attributes.' src="'.$src.'" type="application/x-shockwave-flash">';
			break;
		}
		return $content;
	}
	public function parseCSS(){
		$output='';
		$data=$this->data;
		foreach($data as $n => $d){
			switch($d['type']){
				case 'object':
					$output.=$n.':'.$d['val'].'; '."\n";
				break;
				case 'text':
					$removed_text=str_replace('text-','',$n);
					$workingCSS="";
					$manip=$removed_text;
					if(strpos($manip,"padding")!==false){
						if(strpos($manip,"x")!==false){
							$good_padding=str_replace('x','left',$manip);
							$output.='padding-right :'.$d['val'].'; '."\n";
						}
						if(strpos($manip,"y")!==false){
							$good_padding=str_replace('y','top',$manip);	
							$output.='padding-bottom :'.$d['val'].'; '."\n";							
						}
						$manip=$good_padding;
					}
					if(strpos($manip,"font-color")!==false){
						$good_padding=str_replace('font-','',$manip);
						$manip=$good_padding;
					}
					$workingCSS=$manip;
					$output.=$workingCSS.':'.$d['val'].'; '."\n";
				break;
				case 'transform':
					$removed_text=str_replace('text','',$n);
					$output.=$removed_text.':'.$d['val'].'; '."\n";
				break;
			}
		}
		return $output."\n".' position:absolute; overflow : auto;';
	}
	
	public function extractData($str){
		$dataArray=array();
		$vars = $this->cutVars($str);
		foreach ($vars as $n => $v){
			$data=array();
			$an = $this->cutAttributeName($n);
			if($an){
				if(isset($an['type'])&&isset($an['attr'])){
					$data['type']=$an['type'];
					$data['val']=$v;
					$dataArray[$an['attr']]=$data;
				}
			}
		}	
		return $dataArray;
	}
	
	public function cutVars($str){
		$vars=array();
		$lines = explode("\n",$str);
		foreach($lines as $l){
			$terms = explode(":",$l);
			if(count($terms)==2){
				$n = $terms[0];
				$v = $terms[1];
				if($n!=""&&$v!=""){
					$vars[$n]=$v;
				}
			}else if(count($terms)>2){
				$n = $terms[0];
				$v="";
				for($i=1;$i<count($terms);$i++){
					$v.=$terms[$i];
					if($i<count($terms)-1){
						$v.=':';
					}
				}
				if($n!=""&&$v!=""){
					$vars[$n]=$v;
				}			
			}
		}
		return $vars;
	}
	public function cutAttributeName($str){
		$attributeName = array();
		$cut = explode("-",$str);
		if(count($cut)>=2){
			if(count($cut)==2){
				$attributeName['attr']=$cut[count($cut)-1];
			}else if(count($cut)>2){
				$attributeName['attr']='';
				for($i=1;$i<count($cut);$i++){
					$attributeName['attr'].=$cut[$i];
					if($i<count($cut)-1){
						$attributeName['attr'].='-';
					}
				}
			}
			$attributeName['type'] = $cut[0];
		}
		return $attributeName;
	}	
	
	public function extractType($str){
		$line = explode("\n",$str);
		$couples = array();
		foreach($line as $l){
			
			$terms = explode(":",$l);

			if(count($terms)>=2){
				$couples[$terms[0]]=$terms[1];
			}
		}
		if(isset($couples['type'])){
			return $couples['type'];
		}
		return "no type found";
		
	}
	

}
?>

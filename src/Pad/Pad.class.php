<?php
//<!-- PAD -->
class Pad extends Component {

	//variables 
	private $name;
	private $url;
	
	//consctructeur
    public function __construct($url){
		$this->url=$url;
		$this->name = $this->extractName();
    }
	public function getName(){
		return $this->name;
	}
	private function extractName(){
		$slashExplode = explode('/',$this->url);
		$name = $slashExplode[count($slashExplode)-1];
		return $name;
	}

	private function parseHtml(){
		$file=new File($this->url.'/export/html');
		$messy=$file->read();
		$readable = preg_replace('#(</?.*?>)#',"\n$1\n",$messy);
		$docTagsRemoved = preg_replace('#</?(html|body|\!doctype|head).*?>#','',$readable);
		$final = str_replace(array("<title>","</title>"),array('<div id="pad_title">','</div><br>'),$docTagsRemoved );
		return '<div class="pad" id="'.$this->name.'">'.$final.'</div>';
	}
	private function parseTxt(){
		$file=new File($this->url.'/export/txt');
		$final=$file->read();
		return $final;	
	}
	public function append($format){
		switch($format){
			case 'html':
				echo $this->parseHtml();
			break;
			case 'txt':
				echo $this->parseTxt();
			break;
		}
	}

}
?>

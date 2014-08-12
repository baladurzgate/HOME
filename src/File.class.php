<?php
	/*
						* * * * * * * * * * * * * * *
						*                           *
						*         F I L E           *
						*                           *
						* * * * * * * * * * * * * * *
	*/
class File extends Component {

	//variables 
	private $url;
	private $name;
	private $format;
	private $size;
	private $file_handle;
	private $content;
	private $blocked;
	private $exist;
	
	//consctructeur
    public function __construct($url,$updateLog=true){
		parent::__construct("file");
		if ($this->S->isAllowed($this,$url,$updateLog)) {
			if (!file_exists($this->url)) {
				$this->url=$url;
				$this->content="file content";
				$this->size=strlen($this->content);
				$this->exist = false;
			}else{
				$this->url=$url;
				$this->read();
				$this->size=filesize($this->url);
				$this->exist = true;
			}
			$this->name = $this->extractName($this->url);
			$this->format = $this->extractFormat($this->url);
			if($updateLog){
				$this->Log("new file : ".$this->url);
			}
			return true;
		}else{
			$this->url="";
			return false;

		}
    }
	

	//GETTERS
	public function getURL(){return $this->url;}
	public function getName(){return $this->name;}
	public function getFormat(){return $this->format;}
	public function getContent(){return $this->content;}
	public function getSize(){return $this->size;}
	public function getHTML(){return $this->html;}
	public function allreadyExist(){return $this->exist;}


	//SETTERS
	//url
	public function setURL($url,$updateLog=true){
		if($this->isAllowed($url,$updateLog)){
			$this->url=$url;
			return true;
		}
		return false;
	}
	//html
	public function setHTML($htmlDoc,$updateLog=true){
		if($this->S->isAllowed($this,$htmlDoc,$updateLog)){
			$this->html=$htmlDoc;
			return true;
		}
		return false;
	}
	//content
	public function setContent($content,$updateLog=true){
		if($this->S->isAllowed($this,$content,$updateLog)){
			$this->content=$content;
			return true;
		}
		return false;
	}

	//extraction du nom du fichier
	public function extractName(){
		$urlSplit=explode('/',$this->url);
		$lastSplit = count($urlSplit)-1;
		return $urlSplit[$lastSplit];
	}
	public function extractNakedName(){
		$name=$this->extractName();
		$nameSplit=explode('.',$name);
		return $nameSplit[0];
	}
	//extraction du format du fichier
	public function extractFormat(){
		$name=$this->extractName();
		$nameSplit=explode('.',$name);
		$lastSplit=count($nameSplit)-1;
		return $nameSplit[$lastSplit];
	}
	//chargement du fichier (WIP)
	public function load($updateLog=true){
		if (file_exists($this->url)) {
		
		}else{
			return false;
		}
	}
	
	//creation du fichier sur le serveur
	public function create($updateLog=true){
		if($updateLog){$this->Log("creating file...",'process');}
		if($this->blocked==false){
			if (!file_exists($this->url)) {
				if($this->url!=""){
					$this->file_handle = @fopen($this->url, "w") or die("can't open file");
					if($this->S->isAllowed($this,$this->content,$updateLog)){
						fwrite($this->file_handle ,$this->content);
						$this->size=strlen($this->content);
						$this->Log($this->url." <--file created successfuly !!",'success');
						return true;
					}
					fclose($this->file_handle );
				}			
			}else{
				if($updateLog){$this->Log($this->url."<--Cannot create File , file allready exist !!",'error');}
			}
		}
		return false;
	}	
	
	//lecture du fichier , retourne une string
	public function read($updateLog=true){
		if($updateLog){$this->Log("reading file...",'process');}
		if($this->blocked==false){
			if (file_exists($this->url)) {
				$output="";
				$this->file_handle = fopen($this->url, "r")or die("can't open file");;
				while (!feof($this->file_handle)) {
				   $line = fgets($this->file_handle);
				   $output.=$line;
				}
				fclose($this->file_handle);
				$this->updateContent($output);
				if($updateLog){$this->Log("file red successfuly !",'success');}
				return $output;
			}else{
				if($updateLog){$this->Log("! file do not exist !",'error');}
			}
		}
		if($updateLog){$this->Log("! Cannot read file ! : ".$this->url." file blocked !",'error');}
		return false;
	}
	
	// ecriture du fichier (avec securite)
	public function write($string,$position,$updateLog=false){
		if($updateLog){$this->Log("writing file...",'process');}
		if($this->blocked==false){
			if (file_exists($this->url)) {
				if($this->S->isAllowed($this,$string,$updateLog)){
					$toWrite ="";
					$existing=$this->read($updateLog);
					switch($position){
						case 'over':
							//ecraser
							$toWrite=$string;
						break;
						case 'after':
							//inserer apr鳍
							$toWrite=$existing.$string;
						break;
						case 'before':
							//inserer avant
							$toWrite=$string.$existing;
						break;
					}
					$this->file_handle = fopen($this->url, 'w') or die("can't open file");
					@fwrite($this->file_handle , $toWrite);
					fclose($this->file_handle );
					$this->updateContent($toWrite);
					return true;
				}
			}
		}
		return false;
	}	
	private function updateContent($submit,$updateLog=false){
		if($updateLog){$this->Log("writing file...",'process');}
		if($submit!=$this->content){
			$this->content=$submit;
			$this->size=strlen($this->content);
		}
	}
	//renommer le fichier (WIP)
	public function rename($newName,$updateLog=true){
		if($this->blocked==false){
			if (file_exists($this->url)) {
				if ($this->isAllowed($newName,$updateLog)) {

				}
			}
		}
		return false;
	}	
	//suppression du fichier (avec securite)
	public function delete($updateLog=true){
		$this->Log("deleting file...",'process');
		if($this->blocked==false){
			if (file_exists($this->url)) {
				if ($this->S->isAllowed($this,$this->url,$updateLog)) {
					unlink($this->url);
					return true;
				}
			}
		}
		return false;
	}	


}
?>

<?php
/*
						* * * * * * * * * * * *
						*                     *
						*   D A T A B A S E   *
						*                     *
						* * * * * * * * * * * *
*/
class DataBase extends Component {
	private $name;
	private $format;
	private $tableMap;
	private $feedback;
	private $paths= array();
	
    public function __construct($name='new_database',$format='xml',$updateLog=true) {
		parent::__construct("database");
		$this->name=$name;
		$this->format=$format;
		$this->feedback=new Feedback();
		$this->tableMap = new Map('tables');
		if($updateLog){
			$this->Log("new ".$format." database :".$this->name,"event");
		}
		switch ($this->format){
			case 'xml':
				$this->paths['dir'] = $this->S->paths['S']['databases'].$this->name;
				$this->paths['info'] = $this->paths['dir']."/".$this->name.'-database.xml';
				if($this->allreadyExist()){
					$this->extractMap();
				}			
			break;
			case 'mysql':
			
			
			break;
		}
	
	}
	
	//getters
	public function getName(){
		return $this->name;
	}
	public function getMap(){
		return $this->tableMap;
	}	
	public function getTable($name){
		switch ($this->format){
			case 'xml':
				if(file_exists($this->paths['dir'].$name)){
					if(file_exists($this->paths['dir'].$name.'/table-'.$name.'.xml')){
						$this_table=new Table($name,$this);
						return $this_table;
					}
				}
			break;
			case 'mysql':
			
			
			break;
		}
	}
	public function getTableFromMap($name){
		return $this->tableMap->get($name);
	}	
	
	public function getFormat(){
		return $this->format;
	}
	public function getPath($path){
		if(isset($this->paths[$path])){
			return $this->paths[$path];
		}
		return false;
	}
	public function getFeedback(){
		return $this->feedback;
	}
	public function updateTableMap(){
		$this->tableMap=$this->getAllTables();
	}
	//adders
	public function addMessage($m){
		$this->feedback->addMessage($m,$this->name);
	}
	public function addTables($tableArray){
		foreach ($tableArray as $table){
			$this->addTable($table);
		}
	}
	public function addTable($table){
		if($table->isType('table')){
			if($table->create($this->getFormat(),$this)){
				$map=$this->getMap();
				$map->set($table->getName(),$table);
				$this->updateInfoFile();
			}
		}
	}
	//creators
	
	public function create($type){
		switch($this->getFormat()){
			case 'xml':
				if(!$this->allreadyExist()){
					$this->createDir();
					$this->createInfoFile();
				}else{
					$this->extractMap();
					$this->updateTableMap();				
				}
			break;
			case 'mysql':
			
			break;
		}
	}	
	private function createInfoFile(){
		$infoXMl = $this->parseInfoXML();
		if(!file_exists($this->paths['info'])){	
			$infoXMl->createXML();
			return true;
		}
		return false;
	}
	private function updateInfoFile(){
		$infoXMl = $this->parseInfoXML();
		if(file_exists($this->paths['info'])){		
			$infoXMl->writeXML();	
			return true;
		}
		return false;
	}
	private function createDir(){
		$this->Log('creating dir...',"process");
		$dir = new Dir($this->paths['dir']);
		if(!$dir->allreadyExist){
			$dir->create();
		}
	}

	public function allreadyExist(){
		switch($this->getFormat()){
			case 'xml':
				return file_exists($this->paths['info'])&&file_exists($this->paths['dir']);
			break;
			case 'mysql':
				
			break;
		}

	}
	
	//generators
	private function parseInfoXML(){
		$dom = new DOMDocument('1.0', 'utf-8');
		$root = $dom->createElement('DATABASE');
		$root->setAttribute( "name", $this->S->cleanString($this->name));
		$dom->appendChild($root);
		$map = $dom->createElement('MAP');
		$root->appendChild($map);
		$Tmap = $this->getMap();
		$array = $Tmap->getArray();
		$L = $Tmap->getLength();
		if($L>0){
			foreach($array as $n => $t){
				$table = $dom->createElement('table');
				$table->setAttribute('name',$this->S->cleanString($n));
				$map->appendChild($table);
				$data = $dom->createCDATASection ($n);
				$table->appendChild($data);
			}
		}
		$infoXML = new XML($this->paths['info']);
		$infoXML->setDOM($dom);
		return $infoXML;
	}

	//extractors
	public function extractMap(){
		switch($this->getFormat()){
			case 'xml':
				$this->Log('extracting tables map...',"process");
				$XMLFile = new XML($this->paths['info']);
				$dom=$XMLFile->readDOM();
				$tables=$dom->getElementsByTagName('table');
				if($tables!=NULL){
					foreach($tables as $t){
						if ($t->hasAttributes()){		
							$name = $t->getAttribute('name');
							if($name!=NULL){
								$map=$this->getMap();
								$map->set($name,new Table($name,$this));
							}
						}
					}
				}
			break;
			case 'mysql':
			
			break;
		}

	}
	
	public function appendFeedback(){
		$this->feedback->append();
	}
	
	//reccupere toutes les tables dans une array d'instances de la classe Table
	public function getAllTables($type='xml',$updateLog=true){
		if($updateLog){$this->Log("gathering tables...","process");}
		$output=array();
		switch($type){
			case 'xml':
				$dir=new Dir($this->getPath('dir'));
				$files = $dir->scan("xml","table",false);
				$matching=0;
				if($files){
					foreach($files as $file){
						$xml = new DOMDocument('1.0', 'utf-8');
						$str=$file->read();
						$xml->loadXML($str);
						$root=$xml->getElementsByTagName('TABLE');
						$dbname=$root->item(0)->getAttribute('database');
						if($dbname!==NULL&&$dbname==$this->getName()){
							$name=$root->item(0)->getAttribute('name');
							$matching++;
							array_push($output,$name);
						}
						if($matching==0){
							if($updateLog){$this->Log("no table found","error!");}
						}else{
							if($updateLog){$this->Log("found ".$matching." tables","success");}
							$this->idCount=$matching;
						}
					}
				}else{
					if($updateLog){$this->Log("no table found","error!");}				
				}
			break;
			case 'mysql':
			
			break;
		}
		return $output;
	}
}
?>

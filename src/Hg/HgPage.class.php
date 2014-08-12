<!-- HGPAGE -->
<?php
class HgPage extends Component {

	//variables 
	private $name;
	private $paths= array();
	private $data = array();
	
	
	//consctructeur
    public function __construct($name){
		parent::__construct('mirror');
		$this->name = $name;
		$this->init();
    }
	public function init(){
		$this->paths['I']['dir']='hg/content/'.$this->name.'/';
		$this->paths['I']['objects']=$this->paths['I']['dir'].'head/';
		$this->paths['I']['shared']=$this->paths['I']['dir'].'shared/';
		
		$this->paths['S']['dir']=$this->S->paths['S']['hg'].'content/'.$this->name.'/';
		$this->paths['S']['objects']=$this->paths['S']['dir'].'head/';
		$this->paths['S']['shared']=$this->paths['S']['dir'].'shared/';
		
		$this->paths['C']['dir']=$this->S->paths['C']['hg'].'content/'.$this->name.'/';
		$this->paths['C']['objects']=$this->paths['C']['dir'].'head/';
		$this->paths['C']['shared']=$this->paths['C']['dir'].'shared/';
		
		if(!file_exists($this->paths['S']['dir'])){
			$dir=new Dir($this->paths['S']['dir']);
			$dir->create();
		}
	}
	public function getPath($t,$p){
		if(isset($this->paths[$t][$p])){
			return $this->paths[$t][$p];
		}
		return false;
	}
	public function setPath($t,$p,$u){
		$this->paths[$t][$p]=$u;
	}
	public function insert(){
		$dir = new Dir($this->getPath('S','objects'));
		$objects=$dir->scan();
		foreach($objects as $o){
			if($o->extractName!=='page'){
				$object = new HgObject($o,$this);
				$object->insert();
			}
		}
	}

}
?>

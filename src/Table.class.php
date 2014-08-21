<?php
	/*
						* * * * * * * * *
						*               *
						*   T A B L E   *
						*               *
						* * * * * * * * *
	*/
class Table extends Component {

	//variables 
	private $name;
	private $channelMap;
	private $dataBase;
	private $outputs;
	private $feedback;
	protected $templates;
	protected $paths;
	private $idCount;
	private $postMap;
	private $acountSystem;
	private $nameChannel;
//________________________________________[ C O N S T R U C T O R ]______________________________________|

    public function __construct($name='new_table',$updateLog=true) {
		parent::__construct("table");
		$this->name=$name;
		$this->channelMap=array();
		$this->feedback=new Feedback();
		$this->paths = array();
		$this->templates =  array();
		$this->postMap =  array();
		$this->idCount=0;
		if($updateLog){
			$this->Log("new table :".$this->name,"event");
		}
	}
	
//___________________________________________[ G E T T E R S ]___________________________________________|

	public function getName(){
		return $this->name;
	}
	
	public function noSName(){
		return $noS = substr($this->getName(), 0, -1);	
	}	
	
	public function getPath($path){
		if(isset($this->paths[$path])){
			return $this->paths[$path];
		}
		return false;
	}
	
	public function getDataBase(){
		return $this->dataBase;
	}
	public function linkTo($as){
		$this->acountSystem=$as;
	}
	
	public function refreshPaths(){
		if($this->dataBase!=NULL){
			$root=$this->dataBase->getPath('dir');
		}else{
			$root=$this->S->paths['S']['data'].'/';
		}

		$this->paths['root'] = $root;
		$this->paths['dir'] = $this->paths['root'].'/'.$this->name;
		$this->paths['info'] = $this->paths['dir']."/".$this->name.'-table.xml';
		$this->paths['templates'] =$this->S->template->getServerPath('dir');
		$this->templates['post'] = $this->paths['templates']."/".$this->name.'-post.php';
		$this->templates['form'] = $this->paths['templates']."/".$this->name.'-form.php';
		$this->templates['archives'] = $this->paths['templates']."/".$this->name.'-archives.php';
		$this->templates['single'] = $this->S->template->getClientPath('dir')."/".$this->name.'-single.php';
	}
	
	public function getFeedback(){
		return $this->feedback;
	}
	
	public function getPostMap(){
		return $this->postMap;
	}
	
	public function getPop(){
		return count($this->postMap);
	}	
	
	public function getChannelMap(){
		return $this->channelMap;
	}
	
	public function getPost($id){
		if(count($this->postMap)>0){
			foreach($this->postMap as $t => $p){
				if($p->getValue('id')==$id){
					return $p;	
				}
			}
		}
		return false;
	}
	public function getPosts($id){
		return $this->postMap;
	}	
	public function getPostValue($id,$val){
		if(count($this->postMap)>0){
			foreach($this->postMap as $t => $p){
				if($p->getValue('id')==$id){
					return $p->getValue($val);
				}
			}
		}
		return false;
	}
	
	public function getPostsByValue($channel,$val){
		$matchingPosts = array();
		if(count($this->postMap)>0){
			foreach($this->postMap as $t => $p){
				if($p->getValue($channel)==$val){
					array_push($matchingPosts,$p);	
				}
			}
			if(count($matchingPosts)>0){
				return $matchingPosts;
			}
		}
		return false;
	}
	public function getPostBySerial($sl){
		$matchingPosts = array();
		if(count($this->postMap)>0){
			foreach($this->postMap as $t => $p){
				if($p->getSerial()==$s){
					array_push($matchingPosts,$p);	
				}
			}
			if(count($matchingPosts)>0){
				return $matchingPosts[0];
			}
		}
		return false;
	}	
	
	public function getTemplates($t){
		return $this->templates[$t];
	}
	
//___________________________________________[ S E T T E R S ]_________________________________________|

	public function setDataBase($d){
		$this->dataBase=$d;
	}

//__________________________________________[ C R E A T O R S ]________________________________________|

	public function create($type='xml',$dataBase){
		$this->dataBase=$dataBase;
		$root=$this->dataBase->getPath('dir');
		$template = $this->S->template;
		$templatePath=$template->getServerPath('dir');
		$this->paths['root'] = $root;
		$this->paths['dir'] = $this->paths['root'].'/'.$this->name;
		$this->paths['info'] = $this->paths['dir']."/".$this->name.'-table.xml';
		$this->paths['templates'] =$templatePath;
		$this->templates['post'] = $this->paths['templates']."/".$this->name.'-post.php';
		$this->templates['form'] = $this->paths['templates']."/".$this->name.'-form.php';
		$this->templates['archives'] = $this->paths['templates']."/".$this->name.'-archives.php';
		switch($type){
			case 'xml':
				$this->Log('creating table...',"process");
				if(!$this->allreadyExist()){
					$this->addChannel(new Channel('id',array('type'=>'int','access'=>'none')));
					$this->addChannel(new Channel('timestamp',array('type'=>'int','access'=>'none')));		
					$this->addChannel(new Channel("author",array("type"=>"author","access"=>"archives")));
					$this->createDir();
					$this->updateInfoFile();
					return true;
				}else{
					$this->extractMap();
					return false;
				}
			break;
			case 'mysql':
			
			break;
		}
		return false;
	}
	
	private function createDir(){
		$this->Log('creating dir...',"process");
		$dir = new Dir($this->getPath('dir'));
		if(!$dir->allreadyExist){
			$dir->create();
		}
	}

	private function createPost($post){
		$values = $post->getValues();
		$type = $post->getPostType();
		$timestamp = $values['timestamp'];
		$id = $values['id'];
		$url = $this->paths['dir']."/post_".$timestamp.$id.".xml";
		if($this->checkValues($values)==true){
			$XMLfile=new XML($url);
			$XMLfile->setDOM($this->parsePostXML($type,$values));
			$XMLfile->createXML();
			return true;
		}else{
			$this->Log('values non valid...',"error");
			return false;
		}
	}
	
	public function updatePost($post){
		$timestamp=$post->getValue('timestamp');
		$id = $post->getValue('id');
		$this->Log('updating Post...',"process");
		if($this->writePost($post)!==false){
			$this->updateInfoFile();
			return true;
		}
		return false;
	}	
	
	public function removePost($postID){
		$this->Log('removing post...',"process");
		$post->setValue('id',$this->parsePostID('xml'));
		$post->setValue('timestamp',date("U"));
		if($this->createPost($post)!==false){
			$this->updatePostMap();		
			return true;
		}
		return false;
	}
	
	private function writePost($post){
		$values = $post->getValues();
		$type = $post->getPostType();
		$timestamp = $values['timestamp'];
		$id = $values['id'];
		$url = $this->paths['dir']."/post_".$id.".xml";
		if($this->checkValues($values)==true){
			$XMLfile=new XML($url);
			$XMLfile->setDOM($this->parsePostXML($type,$values));
			if(file_exists($url)){
				$XMLfile->writeXML();
				return true;
			}
			return false;
		}else{
			$this->Log('values non valid...',"error");
			return false;
		}
	}

	protected function updateInfoFile(){
		$dbName = $this->dataBase->getName();
		$dom = new DOMDocument('1.0', 'utf-8');
		$root = $dom->createElement('TABLE');
		$root->setAttribute( "name", htmlspecialchars ($this->name));
		$root->setAttribute( "database", htmlspecialchars ($dbName));
		$dom->appendChild($root);
		$map=$dom->createElement('MAP');
		$root->appendChild($map);
		//channels
		$channelMap = $this->getChannelMap();
		$channels=$dom->createElement('CHANNELS');
		$map->appendChild($channels);
		if(count($channelMap)>0){
			foreach($channelMap as $name => $i){
				$channel = $dom->createElement('channel');
				foreach($i->getAttributes() as $n=>$v){
					$channel->setAttribute($n,htmlspecialchars($v));
				}
				$label = $dom->createElement('label');
				$data = $dom->createCDATASection ($i->getLabel());
				$label->appendChild($data);
				$channels->appendChild($channel);
				$channel->appendChild($label);
			}
		}
		//posts
		$postMap = $this->getPostMap();
		$posts=$dom->createElement('POSTS');
		$map->appendChild($posts);
		if(count($postMap)>0){
			foreach($postMap as $i => $p){
				$post = $dom->createElement('post');
				$post->setAttribute("type",$p->getPostType());
				$post->setAttribute("timestamp",$p->getValue('timestamp'));
				$post->setAttribute("id",$p->getValue('id'));
				$posts->appendChild($post);
			}
		}
		//create xml file
		$infoFile = new XML($this->paths['info']);
		$infoFile->setDOM($dom);
		if(!file_exists($this->paths['info'])){	
			$infoFile->createXML();		
		}else{
			$infoFile->writeXML();		
		}
	}
	
//___________________________________________[ A D D E R S ]___________________________________________|

	public function addChannels($channelsArray){
		foreach ($channelsArray as $channel){
			$this->addChannel($channel);
		}
	}
	
	public function addChannel($channel){
		$this->Log('adding new Channel...',"process");
		if($channel->isType('channel')){
			if(isset($this->channelMap[$channel->getName()])==false){
				$this->channelMap[$channel->getName()]=$channel;
				$channel->setTable($this);
				return true;
			}else{
			
			}return false;
		}
	
	}

	public function addPost($post){
		$timestamp=date("U");
		$id = $this->parsePostID('xml');
		$this->Log('adding new Post...',"process");
		$post->setValue('id',$id);
		$post->setSerial($timestamp.$id);
		$post->setValue('timestamp',$timestamp);
		if($this->createPost($post)!==false){
			$this->postMap[$id]=$post;	
			$this->updateInfoFile();
			return true;
		}
		return false;
	}
	
	public function addMessage($m){
		$this->feedback->addMessage($m,$this->name);
	}
	public function loadPost($post){
		$this->Log('adding new Post...',"process");
		array_push($this->postMap,$post);	
	}	
//___________________________________________[ P A R S E R ]___________________________________________|

	public function parsePostID($format){
		$postMap = $this->getPostMap();
		if($postMap !=NULL){
			return intval(end($postMap)->getValue('id'))+1;
		}else{
			return 0;
		}
	}
	
	protected function parsePostXML($type,$values){
		$dom = new DOMDocument('1.0', 'utf-8');
		$root = $dom->createElement('post');
		$root->setAttribute("type",$this->S->cleanString($type));
		$root->setAttribute("table",$this->S->cleanString($this->getName()));
		$dom->appendChild($root);
		foreach($this->channelMap as $name => $channel){
		   $tag = $dom->createElement ('channel');
		   $v="";
		   if(isset($values[$name])){
			$v=$values[$name];
		   }
		   $data = $dom->createCDATASection($v);
		   $tag->appendChild($data);		   
		   $tag->setAttribute( "name",$this->S->cleanString($name));
		   $tag->setAttribute( "type",$this->S->cleanString($channel->getChannelType()));
		   $root->appendChild($tag);
		}
		return $dom;
	}

	protected function parseInputTag($name){
		$output="";
		if(isset($this->channelMap[$name])){
			$channel = $this->channelMap[$name];
			$output=$channel->parseTag();
		}
		return $output;
	}

	protected function parseForm(){
		$name = $this->getName();
		$noS = $this->noSName();
		$submit_file= $this->S->RequestURL('submit_'.$name);
		$channels = $this->getChannelMap();
		$output='</br><span>New '.$noS.'</span>'."\n";
		$output.='<form id="form-'.$this->name.'">'."\n";
		$output.='<div id="feedback-'.$this->name.'"></div></br>'."\n";
		foreach ($this->channelMap as $name => $channel){
			if(strpos($channel->getAccess(),'form')!==false){
				$output.="	".$this->parseInputTag($name).'</br>'."\n";
			}
		}
		$output.='</form>'."\n";
		$output.=$this->parseButton('preview','PREVIEW');
		$output.=$this->parseButton('submit','SUBMIT');
		return $output;
	}

	public function parseButton($action='submit',$innerHTML='action',$class='bt',$header=''){
		$noS = $this->noSName();
		$submit_file= $this->S->RequestURL('request_'.$this->getName());	
		$onclick='submit(\''.$action.'\',\''.$submit_file.'\',\'form-'.$this->getName().'\',\'feedback-'.$this->getName().'\',\''.$header.'\');';
		$output='<button id="bt-'.$action.'-'.$noS.'" class="'.$class.'" onclick="'.$onclick.'">'.$innerHTML.'</button>'."\n";
		return $output;
	}

//__________________________________________[ R E A D E R ]_____________________________________________|

	public function extractMap(){
		$this->Log('extracting channel map...',"process");
		$XMLFile = new XML($this->paths['info']);
		$dom=$XMLFile->readDOM();
		//channels
		$channels=$dom->getElementsByTagName('channel');
		foreach($channels as $i){
			if ($i->hasAttributes()) {
				$attributes=array();
				foreach($i->attributes as $a){
					$n = $a->nodeName;
					$v = $a->nodeValue;
					$attributes[$n]=$v;
				}
				if(isset($attributes['name'])){
					$name=$attributes['name'];
					$labels=$i->getElementsByTagName('label');
					$labelNode=$labels->item(0);
					$label=$labelNode->nodeValue;
					$nchannel=new Channel($name,$attributes);
					$nchannel->setLabel($label);
					$this->addChannel($nchannel);
				}else{
					$this->Log('the channel has no name!',"error");
				}
			}
		}
		//posts
		$posts=$dom->getElementsByTagName('post');
		foreach($posts as $i => $p){
			if ($p->hasAttributes()) {
				$attributes=array();
				foreach($p->attributes as $a){
					$n = $a->nodeName;
					$v = $a->nodeValue;
					$attributes[$n]=$v;
				}
				if(isset($attributes['id'])&&isset($attributes['timestamp'])){
					$id=$attributes['id'];
					$timestamp=$attributes['timestamp'];
					$url=$this->paths['dir']."/post_".$timestamp.$id.".xml";
					if(file_exists($url)){
						$file = new File($url);
						$xml = new DOMDocument('1.0', 'utf-8');
						$str=$file->read();
						$xml->loadXML($str);
						$roots=$xml->getElementsByTagName('post');
						$type=$roots->item(0)->getAttribute('type');
						$table=$roots->item(0)->getAttribute('table');
						if($table==$this->name){
							$channelNodes=$xml->getElementsByTagName('channel');
							$nPost=new Post($type,$this);
							foreach($channelNodes as $channel){
								$name=$channel->getAttribute('name');
								$value=$channel->nodeValue;
								$nPost->copyValue($name,$value);
							}
							$this->postMap[$nPost->getValue('id')]=$nPost;
						}		
					}
				}
			}
		}
	}
	//traitement des variables externes (POST ou GET)
	protected function readVariables ($type){
		$output=array();
		foreach ($this->channelMap as $channel){
			$name=$channel->getName();
			$variable=new Variable($type,$name);
			if($variable->getValue()){
				$output[$name]=$variable->getValue();
			}else{
				$output[$name]="";
			}
		}		
		return $output;
	}
//________________________________________[ C H E C K E R S ]___________________________________________|

	public function allreadyExist(){
		if(file_exists($this->getPath('info'))&&file_exists($this->getPath('dir'))){
			return true;
		}
		$this->Log('table allready exist...',"error");
		return false;
	}
	
	protected function checkValues($values){
		$this->feedback->erase();
		$errorCount=0;
		$channels=$this->getChannelMap();
		foreach($values as $i => $v){
			if (array_key_exists($i,$channels)){
				if($channels[$i]->checkValue($v)==false){
					$errorCount++;	
					echo $v;
				}
			}else{
				$this->Log('unknown channel "'.$i.'"',"error");			
			}
			$this->feedback->merge($channels[$i]->getFeedback());
		}
		if($errorCount==0){
			return true;
		}else{	
			return false;
		}
		return false;
	}

//______________________________________[ D I S P L A Y E R S ]_________________________________________|


	public function appendForm(){
		$templateUrl=$this->templates['form'];
		if(isset($templateUrl)){
			if(file_exists($templateUrl)){
				$inputs = array();
				foreach ($this->channelMap as $name => $input){
					if(strpos($input->getAccess(),'form')!==false){
						$inputs[$name]=$this->parseInputTag($name)."\n";
					}
				}
				include($templateUrl);
			}else{
				echo $this->parseForm();		
			}					
		}else{
			echo $this->parseForm();		
		}
	}
	
	public function appendFeedback(){
		$this->feedback->append();
	}
	public function appendPosts($posts){
		if($posts!=NULL&&count($posts)>=1){
			$this->Log("including posts...","process");
			foreach($posts as $k => $p){
				echo $p->getValue('post_title');
				$p->append();
			}
			return true;
		}
		return false;
	}
	
	public function filterPosts($posts,$filter){
		if($filter!=NULL&&count($filter)>0){
			$matchingPosts=array();
			$postMap = $posts;
			if($postMap!=NULL&&count($postMap)>0){
				$presitents=array();
				$candidates=array();
				//new election
				foreach($filter as $i => $f){
					if($i==0){
						$candidates=$postMap;
					}else{
						//try another mandate	
						$candidates=$presitents;
						$presitents=array();
					}
					foreach($candidates  as $t => $p){
						//check if election is valid
						if($f->isType('filter')){
							//elect president
							if($f->compare($p->getValues())){
								//go for another mandate
								array_push($presitents,$p);
							}
						}
					}
				}
				//return the presidents that had the most mandates
				return $presitents;
			}
			return false;

		}
		return false;
	}
	
	public function cutPosts($posts,$from,$to){
		$output = array();
		for($i=$from;$i<$to;$i++){
			array_push($output,$posts[i]);
		}
		return $output;
	}
	
	public function all(){
		return $this->postMap;
	}	
	
	public function sortByChannel($posts,$channel,$order=SORT_ASC){
		if($posts!=NULL&&count($posts)>0){
			$arrayToSort=array();
			foreach($posts as $k => $p){
				$elem=array();
				$elem=$p->getValues();
				array_push($arrayToSort,$elem);
			}
			$sortedArray = $this->array_sort($arrayToSort,$channel,$order);
			foreach($sortedArray as $k => $v){
				$sortedPostArray[$k]=$posts[$k];
			}
				return $sortedPostArray;
			}
		return false;
	}	
	
	private function array_sort($array, $on, $order=SORT_ASC){
		$new_array = array();
		$sortable_array = array();

		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}

			switch ($order) {
				case SORT_ASC:
					asort($sortable_array);
				break;
				case SORT_DESC:
					arsort($sortable_array);
				break;
			}

			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}

		return $new_array;
	}
	
//_________________________________[ R E Q U E S T S _ M A N A G E R ]___________________________________| 

	public function submitPost($p=NULL){
		$submittedPost="";
		if($p!=NULL){
			$submittedPost=$p;
		}else{
			$noS = substr($this->getName(), 0, -1);	
			$submittedPost=new Post($noS,$this);
		}
		$requestVar = new Variable('POST','request');
		$headerVar = new Variable('POST','header');
		$request = $requestVar->getValue();
		$header = $headerVar->getValue();
		$url = $this->S->parseURLFor($header,'',$this->S->template->getName(),$this->S->content->getName());
		$currentUser=$this->acountSystem->getCurrentUser();
		$author= "";
		if($currentUser==false){$author="john doe";}else{$author=$currentUser->getValue('username');}
		$POSTVariables = $this->readVariables('POST');
		if($POSTVariables!==false){
			$valid = $this->checkValues($POSTVariables);
			if($request){
				switch ($request){
					case 'submit':
						if($valid!==false){
							$submittedPost->setValues($POSTVariables);
							$submittedPost->setValue('author',$author);
							if($this->addPost($submittedPost)){
								echo "valid";
							}else{
								$this->appendFeedback();
							}
						}else{
							$this->appendFeedback();
						}	
					break;
					case 'preview':
						if($valid!==false){
							$submittedPost->setValues($POSTVariables);
							$submittedPost->setValue('author',$author);
							$submittedPost->append();
						}else{
							$this->appendFeedback();
						}
					break;
					case 'edit':
						if($valid!==false){
							$submittedPost->setValues($POSTVariables);
							$submittedPost->setValue('author',$author);
							$this->appendForm();
						}else{
							$this->appendFeedback();
						}
					break;
				}
			}
		}
	}
}
?>

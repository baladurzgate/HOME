<?php
/*
										* * * * * * * * * * * * * * *
										*                           *
										*          D I R            *
										*                           *
										* * * * * * * * * * * * * * *
*/
class Dir extends Component {

	//variables 
	private $url;
	private $dir_handle;
	private $blocked;
	private $exist;
	
	//construction
    public function __construct($url){
		parent::__construct("dir");
		$this->url=$url;
		$this->Log("new dir : ".$this->url);
		return true;
    }
	
	//GETTERS
	public function getURL(){return $this->url;}
	public function allreadyExist(){return $this->exist;}

	//creation du dossier
	public function create($updateLog=true){
		$this->Log("creating Dir : ".$this->url,'process');
		if($this->blocked==false){
			if (!file_exists($this->url)) {
				if ($this->S->isAllowed($this,$this->url,$updateLog)) {
					mkdir($this->url, 0777, true)or die("can't create dir");
					$this->Log($this->url." : Dir created successfuly !!",'success');
					$this->exist = false;
					return true;
				}
			}else{
				$this->Log($this->url." : Cannot create dir , dir allready exist !!",'error');
				$this->exist = true;
			}
		}
		return false;
	}	
	
	//Lecture du dossier , renvoi une array ou une string. 
	public function read($outputType,$updateLog=true){
		$this->Log("Start reading Dir : ".$this->url."...",'process');
		if (file_exists($this->url)) {
			if ($this->dir_handle = @opendir($this->url)or die("can't open dir")) {	
				$output;
				switch($outputType){
					case 'string':
						$output="";
					break;
					case 'array':
						$output=array();
					break;
				}
				while (false !== ($entry = @readdir($this->dir_handle))) {
					if ($entry != "." && $entry != ".."&& $entry != ".htaccess"&& $entry != ".git") {
						switch($outputType){
							case 'string':
								$output.="$entry\n";
							break;
							case 'array':
								if($nFile = new File($this->url."/$entry")){;
									array_push($output,$nFile);
								}
							break;
						}
					}
				}
				$this->Log("Dir red successfuly!!",'success');
				closedir($this->dir_handle);
				return $output;
			}else{
				$this->Log("unable to read Dir!!",'error');
				return false;
			}
		}
		$this->Log("! Dir do not exist !",'error');
		return false;
	}
	
	public function scan($ext=NULL,$name=NULL,$strict=true){
		$this->Log('scannig dir for "'.$ext.'" and "'.$name.'"...','process');
		$output=array();
		$dirsToScan = array();
		$dirsScanned = array();
		$dirCount=1;
		$matching=0;
		if(array_push($dirsToScan,$this->_scandir($this->url,'a'))){
			for ($i = 0 ; $i<$dirCount;$i++){
				$dirScan=$dirsToScan[$i];
				if($dirScan['f']){
					foreach ($dirScan['f'] as $file){
						if($nFile = new File($dirScan['path'].$file)){
							$nakedName = $nFile->extractNakedName();
							$format = $nFile->extractFormat();
							if($name==NULL&&$ext==NULL){
								array_push($output,$nFile);
								$matching=1;
							}else{
								if($name!=NULL&&$ext==NULL){
									if($strict==true){
										if($nakedName==$name){
											array_push($output,$nFile);
											$matching++;
										}
									}else{
										if (strpos($nakedName,$name)!==false){
											array_push($output,$nFile);
											$matching++;
										}
									}
									
								}
								if($name==NULL&&$ext!=NULL){
									if($format==$ext){
										array_push($output,$nFile);
										$matching++;
									}						
								}
								if($name!=NULL&&$ext!=NULL){
									if($strict==true){
										if($nakedName==$name&&$format ==$ext){
											array_push($output,$nFile);
											$matching++;
										}
									}else{
										if (strpos($nakedName,$name)!==false&&$format==$ext){
											array_push($output,$nFile);
											$matching++;
										}
									}								
								}

							}
						}
					}
				}
				if($dirScan['d']){
					foreach ($dirScan['d'] as $dir){
						if($nDir = new Dir($dirScan['path'].$dir)){
							array_push($dirsToScan,$this->_scandir($dirScan['path'].$dir,'a'));	
							$this->Log("scannig dir ".$dirScan['path'].$dir."...",'process');
							$dirCount++;
						}
					}
				}
			}
			array_push($dirsScanned,$dirScan);	
		}else{
			$this->Log('cannot scan dir!','error');
		}
		if($matching==0){
			$this->Log('no matching files','error');
			return false;
		}
		$this->Log("scan complete",'event');
		return $output;
	}
	public function getDirs(){
		$scan=$this->_scandir($this->url);
		return $scan['d'];
	}
	public function searchFile($file){
		$this->Log("searching for '".$file."' in dir ".$this->url."...",'process');
		$fileSearched = new File($file);
		return $this->scan($fileSearched->extractFormat(),$fileSearched->extractNakedName());
	}
	
	//fonction pour scanner des dossiers (editeur : Laurent1133  page:http://php.developpez.com/telecharger/detail/id/1455/Scandir-iso-utf8)
	private function _scandir( $path = NULL , $option = NULL, $encodage = 1 ){
	 
		if( is_null( $path ) || empty( $path ) ){
			$path = dirname( __FILE__ );
		}
		 
		$path = rtrim( $path, '\\' );
		$path = rtrim( $path, '/' );
		$path = $path.'/';
		 
		$scan = @scandir( $path );
		 
		 
		if( $scan == false ){
			$scan = @scandir( utf8_decode( $path ) );
		}
		if( $scan == false ){
			$scan = @scandir( utf8_encode( $path ) );
		}	
		if( $scan == false ){	
			return false;
		}	
		 
		unset( $scan[ array_search( '.', $scan ) ] );
		unset( $scan[ array_search( '..', $scan ) ] );	
		 
		$rep = array( 'd'=>array(), 'f'=>array() );
		 
		$option = strtolower( $option );
		$ext = NULL;
		 
		if( strlen( $option ) > 1 || $option == '.' ){
			$ext = trim( $option, '.' ) ;
			$option = 1;
		}
		 
		foreach( $scan as $k=>$el ){	
		 
			switch( $encodage ){
				case 1:
				$el = utf8_encode( $el );
				break;
				case 2:
				$el = utf8_decode( $el );
			}	
			 
			if( is_dir( $path.$el ) ){
				array_push( $rep['d'], $el );
			}else{
				if( $ext == NULL && $option != 1 ){
					array_push( $rep['f'], $el );
				}
				else{
					if( $ext == pathinfo( $path.strtolower( $el ) , PATHINFO_EXTENSION ) ){
						array_push( $rep['f'], $el );
					}	
				}
			}
		 
		}
	 
		switch( $option ){
			case 'a':
			natcasesort( $rep['d'] );
			natcasesort( $rep['f'] );
			break;
			
			case 'z':
			natcasesort( $rep['d'] );
			natcasesort( $rep['f'] );
			$rep['d'] = array_reverse( $rep['d'] );
			$rep['f'] = array_reverse( $rep['f'] );
		}
		 
		if( !isset( $rep['d'][0] ) || $option == 1 ){
			$rep['d'] = NULL;
		}
		if( !isset( $rep['f'][0] ) ){
			$rep['f'] = NULL;
		}
		 
		$rep['path'] = $path;
		return $rep;
	}
		
	//renommer le dossier (WIP)
	public function rename($newName,$updateLog=true){
		if($this->blocked==false){
			if (file_exists($this->url)) {
				if ($this->S->isAllowed($this,$newName,$updateLog)) {
					return true;
				}
			}
		}
		return false;
	}	
	//supprimmer le dossier	
	public function delete($updateLog=true){
	$this->Log("deleting :".$this->url);
		if($this->blocked==false){
			if (file_exists($this->url)) {
				if ($this->S->isAllowed($this,$this->url,$updateLog)) {
					if (!is_dir($this->url)) {
						rmdir($this->url);
						return true;
					}
				}
			}else{
				$this->Log($this->url." : Cannot delete dir , dir do not exist !!",'error');
			}
		}
		return false;
	}	

}
?>

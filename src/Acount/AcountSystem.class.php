<?php
	/*
										* * * * * * * * * * * * * * * * * 
										*                               *
										*   A C O U N T _ S Y S T E M   *
										*                               *
										* * * * * * * * * * * * * * * * * 
	*/
class AcountSystem extends Table{
	
	private $currentUser;
	//constructor
    public function __construct($name,$database=NULL){
		parent::__construct($name,$database);
		$this->Log("new acountsystem","event");
		$this->init();
		$this->askSession();
    }
	public function init(){
		$channels = array();
		$channels['username']=new Channel("username",array("type"=>"nickname","access"=>"form-archives","mandatory"=>"true","minChar"=>"3","maxChar"=>"20"));
		$channels['password']=new Channel("password",array("type"=>"mp","access"=>"form","treatment"=>"crypted","mandatory"=>"true","minChar"=>"3"));
		$channels['email']=new Channel("email",array("type"=>"email","access"=>"form","treatment"=>"crypted","mandatory"=>"true","minChar"=>"3"));
		$channels['status']=new Channel("status",array("type"=>"status","access"=>"archives"));
		$channels['acount']=new Channel("acount",array("type"=>"status","access"=>"archives"));
		$this->addChannels($channels);
	}
	
	private function addAcount($ua){
			$ua->setValue('acount','actif');
			$ua->acountSystem=$this;
			if($this->addPost($ua)){
				$this->Log("acount added !","success");
				$this->addMessage("acount added");
				return true;
			}
	}
	
	public function getCurrentUser(){
		if($this->askSession()!==false){
			return $this->currentUser; 
		}
		return false;
	}
	public function setCurrentUser($ua){
		$this->currentUser=$ua; 

	}
	
	public function askSession(){
		$_sessionName = new Variable('SESSION',$this->getName().'-username');
		$_sessionMP = new Variable('SESSION',$this->getName().'-password');
		$sessionName =$_sessionName->getValue();
		$sessionMP =$_sessionMP->getValue();	
		if($sessionMP&&$sessionName){
			$submittedAcount=new UserAcount($this);
			$submittedAcount->setValue('username',$sessionName);
			$submittedAcount->setValue('password',$sessionMP);

			$matchingAcount = $this->acountAllreadyExist($submittedAcount);
			// si ce compte existe
			if($matchingAcount!==false){
				// et si  il est actif
				if($matchingAcount->getValue('acount')=='actif'){	
					//on renseigne la variable CurrentUser$
					$this->setCurrentUser($matchingAcount);
					return true;

				}
			}else{
				return false;
			}
		}
		return false;
	}
	
	private function controlAcount($ua){
		$this->Log("controling user acount...","process");
		if(isset($ua)){
			if($ua->isType('acount')){
				if($this->acountAllreadyExist($ua)==false){	
					if($this->checkValues($ua->getValues())!==false){
						return true;
					}
					$this->Log("3-error","error");
					return false;
				}
				$this->Log("2-error","error");
				return false;
			}
			$this->Log("argument is not of type [acount]!","error");
			return false;
		}
		$this->Log("1-error","error");
		return false;	
	}
	
	// traite les requettes 
	public function dealWithRequest(){

		$this->Log("-----SESSION REQUEST-----","process");
		//on reccupere la requette
		$_request = new Variable('POST','request');
		$request = $_request->getValue();

		// on declare un nouvel objet userAcount
		$submittedAcount=new UserAcount($this);	
		
		$_postName = new Variable('POST','username');
		$_postMP = new Variable('POST','password');
		$postName =$_postName->getValue();
		$postMP =$_postMP->getValue();

		//si la page a reçu une requette
		if($request){
			// si il s'agit de login
			if($request=='login'||$request=='logout'){
				
				$_sessionName = new Variable('SESSION',$this->getName().'-username');
				$_sessionMP = new Variable('SESSION',$this->getName().'-password');

				$sessionName =$_sessionName->getValue();
				$sessionMP =$_sessionMP->getValue();
				

				
				if($request=='logout'){
					$submittedAcount->setValue('username',$sessionName);
					$submittedAcount->setValue('password',$sessionMP);
					$matchingAcount = $this->acountAllreadyExist($submittedAcount);
					if($matchingAcount!==false){
						//on vide les les variables de la session 
						$_sessionName->setValue("");			
						$_sessionMP->setValue("");	
						$session = new Session();
						$session->delete();
						//on initialise currentUser
						$this->setCurrentUser = false;
						//on change le status du membre 
						$matchingAcount->setValue('status','offline');
						$this->updatePost($matchingAcount);
						echo "valid";
						return true;
					}else{
						//on vide les les variables de la session 
						$_sessionName->setValue("");			
						$_sessionMP->setValue("");	
						//on initialise currentUser
						$this->setCurrentUser = false;
						//on change le status du membre 
						echo "valid";
						return true;			
					}
				}
				//si des variables de login sont passées par post
				if($postName&&$postMP){

					// on renseigne le compte à verifier avec 
					$submittedAcount->setValue('username',$postName);
					$submittedAcount->setValue('password',$postMP);

					// on demande si ce compte existe
					$matchingAcount = $this->acountAllreadyExist($submittedAcount);

					// si ce compte existe
					if($matchingAcount!==false){
						// et si  il est actif
						if($matchingAcount->getValue('acount')=='actif'){
							switch ($request){
								case 'login':
									//on renseigne les variables de la session 
									$this->Log("setting session values","process");
									$_sessionName->setValue($matchingAcount->getValue('username'));			
									$_sessionMP->setValue($matchingAcount->getValue('password'));	
									//on renseigne la variable CurrentUser
									$this->setCurrentUser = $matchingAcount;
									//on change le status du membre 
									$matchingAcount->setValue('status','online');
									$this->updatePost($matchingAcount);
									echo "valid";
									return true;
								break;

							}
						}
					}else{
						$this->addMessage('wrong username or password*');
						$this->appendFeedback();
					}
				}else{
					$this->addMessage('wrong username or password**');
					$this->appendFeedback();
				}
				
			// si il s'agit de créer/modifer un compte
			}else if($request=='submit'||$request=='update'||$request=='preview'){
				$POSTVariables = $this->readVariables('POST');
				if($POSTVariables!==false){
					$validValues = $this->checkValues($POSTVariables);
					
					switch ($request){
						case 'submit':
							if($validValues!==false){
								$email = $POSTVariables['email'];
								$submittedAcount->setValues($POSTVariables);
								if($this->acountAllreadyExist($submittedAcount)==false){
									if($this->addAcount($submittedAcount)){
										echo "valid";
										//$this->sendConfirmationMail();
									}else{
										$this->appendFeedback();
									}
								}else{
									$this->appendFeedback();
								}
							}else{
								$this->appendFeedback();
							}
						break;
						case 'update':
							if($validValues!==false){
								$submittedAcount->setValues($POSTVariables);
								if($this->acountAllreadyExist($submittedAcount)){
									if($this->addAcount($submittedAcount)){
										echo "valid";
									}else{
										$this->appendFeedback();
									}
								}else{
									$this->appendFeedback();
								}
							}else{
								$this->appendFeedback();
							}
						break;
						case 'preview':
							if($validValues!==false){
								$submittedAcount->setValues($POSTVariables);
								if($this->acountAllreadyExist($submittedAcount)){
									$this->appendFeedback();
								}else{
									$this->appendFeedback();
								}
							}else{
								$this->appendFeedback();
							}
						break;
					}
				}
			}
		}
	}	

	public function acountAllreadyExist($ua){
		$acounts=$this->getPostMap();
		if(count($acounts)>0){
			foreach($acounts as $a){
				if(sha1($ua->getValue('email'))==$a->getValue('email')){
					$this->Log("acount allready exist-2","info");
					$this->addMessage("acount allready exist!");
					return $a;									
				}else if($ua->getValue('username')==$a->getValue('username')){
					$this->Log("acount allready exist-1","info");
					$this->addMessage("acount allready exist!");
					return $a;
				}
			}
			$this->Log("there is no such acount","info");
			return false;
		}else{
			$this->Log("no acounts in table","info");
			return false;
		}
		return false;
	}
	public function parseLoginForm(){
		$name = $this->getName();
		$noS = substr($this->getName(), 0, -1);
		$submit_file= $this->S->RequestURL('submit_'.$this->getName());	
		$channels = $this->getChannelMap();
		$output='<form id="form-'.$name.'">'."\n";
		$output.='<div id="feedback-'.$name.'"></div></br>'."\n";
		$output.="	".$this->parseInputTag('username')."\n";
		$output.="	".$this->parseInputTag('password').'</br>'."\n";
		$output.='</form>'."\n";
		$output.=$this->parseButton('login','LOGIN','bt');
		return $output;		
	}

	public function appendLoginForm(){
		$this->templates['login-form']=$this->getPath('templates')."/".$this->getName().'-login-form.php';
		$templateUrl = $this->templates['login-form'];
		if(file_exists($templateUrl)){
			$inputs = array();
			$inputs['username']=$this->parseInputTag('username')."\n";
			$inputs['password']=$this->parseInputTag('password')."\n";
			include($templateUrl);
		}else{
			echo $this->parseLoginForm();		
		}
	}
	
	public function parseConfirmationMail($url){
		$this->templates['conf-mail']=$this->getPath('templates')."/".$this->getName().'-conf-mail.html';
		$templateUrl = $this->templates['conf-mail'];
		$confirmationURL = $url;
		$confirmationLink = '<a href="'.$confirmationURL.'">CONFIRMER</a>';
		$message = $confirmationLink;
		if(file_exists($templateUrl)){
			$file = new File($templateUrl);
			$mailTemplate = $file->read();
			$message = str_replace('[confirm]',$confirmationLink,$mailTemplate);
		}else{
		
		}
		return $message;		
	}
	public function sendConfirmationMail($adress,$url){
		$message = $this->parseConfirmationMail($url);
		mail($adress, $this->S->getName(), $message);	
	}
}
?>
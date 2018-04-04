<?php
class acl_init extends SimpleACL {
	private $initfile ="";
	
	public function __construct($Pinitfile,$Puser=0,$PLevel=1) {
		
		parent::__construct($Puser,$PLevel);
		$this->initfile=$Pinitfile;
		$this->loadini();
	}
	
	private function loadini() {
		$config = new IniConfig($this->initfile,true);
		$TabRessource = array();
		
		foreach ($config->iniValues as $key=>$elem) { 
                if(is_array($elem)) 
                { 
                   foreach ($elem as $key2=>$elem2) { 
                       $TabRessource[$key.'.'.$key2]=intval($elem2);
                    } 
                } else{
					$TabRessource[$key]=intval($elem);
				}
         } 
		$this->adminLevel = $TabRessource['useracl.adminLevel'];
		$this->addResource($TabRessource);

	}
}
?>
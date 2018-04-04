<?php

/**
 * User access layer class.
 *
 * This is a simple user access layer resource based on level group.
 * but also can work witout level groups
 * 
 * Simple License Agreement:
 * THE INFORMATION SUPPLIED AND THE SCRIPT(S) ARE PROVIDED "AS IS", 
 * WITHOUT WARRANTY OF ANY KIND, NOT EVEN THE IMPLIED WARRANTY OF MERCHANTABILITY
 * OR FITNESS FOR A PARTICULAR PURPOSE. 
 *
 * Last version of this scrip released on 7th of July, 2009
 *
 * 
 * @copyright  2008 Tal Cohen
 * @author     Tal Cohen MSN: tal7814@hotmail.com
 * @version    0.2.2009.07.07
 * @since 2008.04.10
 * 
 *
 * @example see example in external attached page example.php and example_extend.php
 * @link http://www.phpclasses.org/browse/file/22673.html
 *
 * 
 * Added\Fixed in last version:
 * -Fixed some minor bugs
 * -Added better debug mode, now it easier to test class setting
 *
 *
 * 
 * Don't forget to test your script before publshing it online!
 * Don't forget to set debug mode to false for other users thwn admin!
 *
 */ 


class SimpleACL {


   /**
    * debug mode.
    *
    * @var boolean
    */
   public $debug = false;

   
   
   /**
    * set true if you want script 
    * to print debug notes in html
    * 
    * @var Bool
    */
   public $printDebugNotes = true;
   
   
   
   /**
    * debug notes
    * 
    * @var Array
    */
   protected $debugNotes = array();
   

   
   /**
    * Holds user access level, default=1
    *
    * 1 => Guest
    * 2 => Member
    * 3 => Staff
    * 4 => Publisher
    * 5 => Admin
    *
    * @var Integer
    */
   protected  $userLevel = 1;



   /**
    * Holds admin level number as using in your system. default=5
    *
    * @var integer admin level
    */
   public $adminLevel = 5;



   /**
    * Holds user ID number
    *
    * @var Integer
    */
   protected $userId = 0;



   /**
    * Resources array
    *
    * @var Array
    */
   protected $resources = array();



   /**
    * Set individual user access to resource
    *
    * @var Array
    */
   protected  $allowUser = array();



   /**
    * Set individual level access to resource
    *
    * @var Array
    */
   protected  $allowLevel = array();



   /**
    * Constractor
    *
    * @param Integer $userId
    * @param Integer $userLevel
    */
   public function __construct($userId=0,$userLevel=1){

      $this->userId = $userId;

      $this->userLevel = $userLevel;
      
      $this->addDebug("initialize class with userid '$userId' and user level '$userLevel' (line:".__LINE__.")");
   }
	
   /**
    * Add a new resource
    *
    * this is the only function where user inherit privilege based on user level
    *
    * @param String $resourceName
    * @param Integer $minimunAccessLevel
    */
   public function addResource($resourceName,$minimunAccessLevel = 1){
      if (is_array($resourceName)) {
    
      	foreach($resourceName as $key=>$value){
             
            //set default minimunAccessLevel if empty
            if (!is_int($value)){//(empty($value)) {
               $this->resources[$value] = $minimunAccessLevel;
               $this->addDebug(">addResource() - add new resource '$value' with min access level '$minimunAccessLevel' (line:".__LINE__.")1");
            }
            else {
               $this->resources[$key] = $value;               
               $this->addDebug(">addResource() - add new resource '$key' with min access level '$value' (line:".__LINE__.")2");
            }
      	}
         
      }
      else {
         $this->resources[$resourceName] = $minimunAccessLevel;
         
         $this->addDebug(">addResource() - add new resource '$resourceName' with min access level '$minimunAccessLevel' (line:".__LINE__.")");
      }
	  //die(print_r($resourceName));
	  //die(print_r($this->resources));
   }

	public function GetResource($value){
		$minimunAccessLevel = 1;
		if (array_key_exists($value,$this->resources)) {
			$minimunAccessLevel = $this->resources[$value];
		}
		return $minimunAccessLevel;
	}
   /**
    * Allow current user access an resource not depending on $minimunAccessLevel
    *
    * @param $resourceName
    * @param Boolean $bool
    */
   public function allowUser($resourceName,$bool=true){
      
      if (is_array($resourceName)) {
         foreach ($resourceName as $name) {
            $this->allowUser[$name] = $bool;
            $this->addDebug(">allowUserAccess() - ".($bool===true?"Allow":"Deny")." access to resource '$name' for current user (line:".__LINE__.")");
         }
      }
      else {
         $this->allowUser[$resourceName] = $bool;
         $this->addDebug(">allowUserAccess() - ".($bool===true?"Allow":"Deny")." access to resource '$resourceName' for current user (line:".__LINE__.")");
      }
   }



   /**
    * Allow user level to access an resource not depending on $minimunAccessLevel
    *
    * @param $resourceName
    * @param unknown_type $bool
    */
   public function allowLevel($resourceName,$bool=true,$userLevel=''){
      if (empty($userLevel)) {
         $userLevel = $this->userLevel;
      }
      $this->allowLevel[$resourceName] = array($bool,$userLevel);
      $this->addDebug(">allowAccess() - ".($bool===true?"Allow":"Deny")." access to resource '$resourceName' for level '$userLevel' (line:".__LINE__.")");
   }



   /**
    * true if admin, false if not
    *
    * @return boolean
    */
   public function isAdmin(){
      if ($this->adminLevel == $this->userLevel) {
         return true;
      }
      else return false;
   }


   /**
    * Check if user is allowed to use resource
    * 
    * Note: script access procedure:
    *  1. check for user access
    *  2. check level access
    *  3. check for global access
    *  
    *  
    *   if user have user access (allowUser('post')) deny his  
    *   level (allowLevel('post',fasle)) will not affect 
    *
    * @param String $resourceName
    * @return Boolean
    */
   public function isValid($resourceName){

      //user is admin
      if ($this->isAdmin()) {
      	 $this->addDebug("-isValid() - admin user! can access any resource (line:".__LINE__.")");
         return true;
      }

      
   	  //check if resource exists
      if (empty($this->resources) && empty($this->allowUser) && empty($this->allowLevel)) { 
         $this->addDebug("*isValid()* '$resourceName' is NOT DEFINED! please add resource first. (line:".__LINE__.")");
         return false;                         
      }
      

      
      //check for individual allowUser() access
      if (isset($this->allowUser[$resourceName]) && $this->allowUser[$resourceName] === true) {      	 
      	 $this->addDebug("-isValid()- check \$allowUser, user can access resource '$resourceName' ".__LINE__.")");
         return true;
         exit;
      }
      elseif (isset($this->allowUser[$resourceName]) && $this->allowUser[$resourceName] === false) {      	
      	$this->addDebug("-isValid()- check \$allowUser, user CAN'T access resource '$resourceName' ".__LINE__.")");
      	return false;
      }
      
      //check for individual user level allowLevel() access
      //good user level
      if (isset($this->allowLevel[$resourceName]) && $this->allowLevel[$resourceName][0] === true) {
         if (is_array($this->allowLevel[$resourceName][1])) {
            foreach ($this->allowLevel[$resourceName][1] as $value) {
               if ($value == $this->userLevel) {               	  
               	  $this->addDebug("-isValid()- check \$allowLevel, user can access resource '$resourceName' level '$value' (line:".__LINE__.")");
                  return true;
               }
            }
         }
         //good user level
         else {
            if ($this->allowLevel[$resourceName][1] == $this->userLevel) {               
               $this->addDebug("-isValid()- check \$allowLevel, user can access resource '$resourceName' level '{$this->allowLevel[$resourceName][1]}' (line:".__LINE__.")");
               return true;
            }
         }
      }
      //no access for current user level
      elseif (isset($this->allowLevel[$resourceName]) && $this->allowLevel[$resourceName][0] === false) {
      	$this->addDebug("-isValid()- check \$allowLevel, user CAN'T access resource '$resourceName', You set block for his level '$this->userLevel' (line:".__LINE__.")");
      	return false;
      }
      
      //check for addResource() global resource access
      if (isset($this->resources[$resourceName])) {
         //$this->addDebug("-isValid()- check resourced global access. (line:".__LINE__.")");
         
         if ($this->userLevel >= $this->resources[$resourceName] && $this->resources[$resourceName] != 0 ) {                  	
         	$this->addDebug("-isValid()- check \$resources - user can access resource '$resourceName' min access '".$this->resources[$resourceName]."'(line:".__LINE__.")");
            return true;
         }
         else {
         	 $this->addDebug("-isValid()- check \$resources - user level '$this->userLevel' CANT'T access resource '$resourceName' min access '".$this->resources[$resourceName]."' (line:".__LINE__.")");
	         return false;
         }
      } 
      else {
	      $this->addDebug("-isValid()- \$resources array is not exists, user CAN'T access resource '$resourceName' (line:".__LINE__.")");
	      return false;
      }
   }
   
   
  	/**
   	 *add new debug  
   	 */
	public function addDebug($string){
		if ($this->debug === true) {
			$this->debugNotes[] = $string;
		}
	}
	
	
	/**
	 * get debug notes in array.
	 * @return Array
	 */
	public function getDebugArray(){
		if ($this->debug === true && !empty($this->debugNotes)) {
			return $this->debugNotes;
		}
	}
	
	
	/**
	 * get debug notes in html
	 */
	 function getDebugHtml(){
		$out = array();
		
		if ($this->debug === true && !empty($this->debugNotes)) {
			foreach($this->debugNotes as $debugLine){
				$out[] = '<div style="padding:2px;margin:4px; border:1px #cccccc solid; width:500px;">'.$debugLine.'</div>';	
			}	
			return implode("",$out);
		}	
	}
	
	
	/**
	 * print debug notes on end
	 * @return Html
	 */
   function __destruct(){
   	if ($this->debug ===true && $this->printDebugNotes === true){
   		print $this->getDebugHtml();	
   	}   	
   }
}


?>
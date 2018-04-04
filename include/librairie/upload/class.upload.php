<?php
// ----------------------------------------------------------------------
// File Management Class
// Copyright (C) 2010 by Junior Elice
// http://www.zeazar.com
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation;
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/licenses/gpl-3.0.txt
// ----------------------------------------------------------------------

/**
    ---  CREATE FILE OBJECT   ---
    $c = new file();
    
    ---  CREATE/UPDATE COOKIE   ---
    $c->setName('myCookie')         // REQUIRED
      ->setValue($value,true)       // REQUIRED - 1st param = data string/array, 2nd param = encrypt (true=yes)
      ->setExpire('+1 hour')        // optional - defaults to "0" or browser close
      ->setPath('/')                // optional - defaults to /
      ->setDomain('.domain.com')    // optional - will try to auto-detect
      ->setSecure(false)            // optional - default false
      ->setHTTPOnly(false);         // optional - default false
    $c->createCookie();             // you could chain this too, must be last
        
    ---  DESTROY COOKIE   ---
    $c->setName('myCookie')->destroyCookie();
    OR
    $c->destroyCookie('myCookie');
    
    ---  GET COOKIE   ---
    $cookie = $c->getCookie('myCookie',true);   // 1st param = cookie name, 2nd param = whether to decrypt
                                                // if the value is an array, you'll need to unserialize the return

*/

class Files {
	
	private $errors = array();
	private $fileName = null;
	private $fileExtension = null;
	private $fileDirectory = null;
	private $fileType = null;
	private $fileSize = 0;
	private $fileWidth = 0;
	private $fileHeight = 0;
	private $fileMethod = null;
	private $autorizeDoc = array ('docx', 'doc', 'xlsx', 'xls', 'csv', 'pdf');
	private $autorizeImage  = array ('jpg', 'jpeg', 'gif', 'png');
	private $accessLog = 'file-access.log';
	private $errorLog = 'file-error.log';
	
	/**
     * Contructor. Set up log folder in HARD
     * @access public
     * @return none
     */
    public function __construct($logDirectory)
    {
        $this->accessLog = $logDirectory.$this->accessLog;
        $this->errorLog = $logDirectory.$this->errorLog;
    }
	
    
    
	/**
	 * Define the file name and the file extension
	 * @access public
	 * @param string $name file name 
	 */
    public function setName ($name=null) {
    	if(!is_null($name)) {
    		$this->fileName = $name;
    		$this->setExtension($this->fileName);
    		$this->setSize($this->fileName);
    		return $this;
    	}
    	$this->pushError('File name was empty');
    	return false;     	
    }	
    
    
    /**
     * Define the file extension
     * @access private
     * @param string $file the file to check the exstension
     */
    private function setExtension ($file=null) {
    	if(!is_null($file)) {
    		$this->fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    		return $this;
    	}
    	$this->pushError('File extension was empty');
    	return false;
    }
    
    
    /**
     * Define the file's directory
     * @access public
     * @param string $directory file location
     */
    public function setDirectory ($directory=null) {
    	if(!is_null($directory)) {
    		$this->fileDirectory = $directory;
    		return $this;
    	}
    	$this->pushError('File directory was empty');
    	return false;
    }
    
    
    /**
     * Define the file type
     * @access public
     * @param string $type file type
     */
    public function setType ($type=null) {
    	if(!is_null($type)) {
    		$this->fileType = $type;
    		return $this;
    	}
    	$this->pushError('File type was not configure');
    	return false;
    }
    
    
    /**
     * Define the file size
     * @access private
     * @param string $file to check the size
     */
    private function setSize ($file=null) {
    	if(!is_null($file)) {
    		if(file_exists($this->fileDirectory.$file)) {
				$units = array('B', 'KB', 'MB', 'GB', 'TB');
				$size = max(filesize($this->fileDirectory.$file));
				$pow = floor(($size ? log($size) : 0) / log(1024));
				$pow = min($pow, count($units) - 1);
				$humanSize /= pow(1024, $pow);
				$this->fileSize = $size;
				return round($humanSize, 2) . ' ' . $units[$pow];
    		}
    		$this->pushError('File was not exist on the server');
    		return false;
    	}
    	$this->pushError('Missing parameter');
    	return false;
    }
    
    
    /**
     * Define the file dimension if the file is an image
     * @access public
     * @param string $file to check the size
     */
    public function setImageSize($file=null) {
    	if(!is_null($file)) {
    		if(file_exists($this->fileDirectory.$file)) {
    			$imageInfos = getimagesize($this->fileDirectory.$file);
    			$this->fileWidth = $imageInfos[0];
    			$this->fileHeight = $imageInfos[1];
    			 return $this;
    		}
    		$this->pushError('File was not exist on the server');
    		return false;
    	}
    	$this->pushError('Missing parameter');
    	return false;
    }
    
    
    /**
     * Define transfert method for the file
     * @access public
     * @param string $methode transfert method GET, POST
     */
    public function setMethod($method=null) {
    	if(!is_null($method)) {
    		if(($method == "GET") || ($method == "POST")) {
    			$this->fileMethod = $method ;
    			return $this;
    		}
    		$this->pushError('File method is not valid');
    		return false;
    	}
    	$this->pushError('Missing file method parameter');
    	return false;
    }
    
    
    /**
     * Add error to errors array
     * @access public
     * @param string $error
     * @return none
     */
    private function pushError($error=null)
    {
        if(!is_null($error)){
            $this->errors[] = $error;
        }
        return;
    }
    
    
	/**
     * Retrieve errors
     * @access public
     * @return string errors
     */
    public function getErrors()
    {
        return implode("<br />", $this->errors);
    }
}
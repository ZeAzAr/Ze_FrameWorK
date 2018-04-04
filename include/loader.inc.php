<?php

class loader{

	private $files_array;
	private $type;
	private $method;
	public $ThrowExceptions = false;
	private $error_desc     = "";       // last mysql error string
	private $error_number   = 0;        // last mysql error number
	
	public function __construct($files_string=null,$method='require_once',$type){
		if(!$files_string) return false;
		$this->files_array = preg_split("/,/",$files_string);
		$this->method = $method; 
		$this->type = $type;
		$tmpNameFile = "";
		$this->ResetError();
		foreach($this->files_array as $file_path){
			switch ($this->type) {
				case "application":
					$tmpNameFile = getcwd().'/include/application/'.trim($file_path).'.php';
					break;
			
				case "language":
					$tmpNameFile = getcwd().'/include/language/AVM_'.trim($file_path).'.php';
					break;
					
				case "page":
					$tmpNameFile = getcwd().'/include/page/page_'.trim($file_path).'.php';
					break;
			
				case "librairie":
					$tmpNameFile = getcwd().'/include/librairie/'.trim($file_path).'/class.'.trim($file_path).'.php';
					break;
					
				case "modules":
					$tmpNameFile = getcwd().'/include/modules/'.trim($file_path).'.php';
					break;
			default: 
					$tmpNameFile = getcwd().'/include/modules/'.trim($file_path).'/class.'.trim($file_path).'.php';
			}
			if (file_exists($tmpNameFile)) {
				eval($this->method.'($tmpNameFile);');
			} else {
				$this->SetError($tmpNameFile,1);
				//die($tmpNameFile);
			}
		}
		// CL loader automatique de class
		spl_autoload_register(array('loader','autoload'));
	}
	static public function autoload($class_name) {
		$LoadClass = getcwd().'/include/librairie/'.trim($class_name).'/class.'.trim($class_name).'.php';
		if (file_exists($LoadClass)) {
			require_once $LoadClass;
		} else { 
			$LoadClass = getcwd().'/include/application/'.trim($class_name).'/class.'.trim($class_name).'.php';
			if (file_exists($LoadClass)) {
				require_once $LoadClass;
			} else {
					die($class_name);
			}
			
		}
	}
	
	private function ResetError() {
		$this->error_desc = '';
		$this->error_number = 0;
	}
	public function Error() {
		$error = $this->error_desc;
		if (empty($error)) {
			if ($this->error_number <> 0) {
				$error = "Unknown Error (#" . $this->error_number . ")";
			} else {
				$error = false;
			}
		} else {
			if ($this->error_number > 0) {
				$error .= " (#" . $this->error_number . ")";
			}
		}
		return $error;
	}

	/**
	 * Returns the last MySQL error as a number
	 *
	 * @return integer Error number from last known error
	 */
	public function ErrorNumber() {
		if (strlen($this->error_desc) > 0)
		{
			if ($this->error_number <> 0)
			{
				return $this->error_number;
			} else {
				return -1;
			}
		} else {
			return $this->error_number;
		}
	}
	
	private function SetError($errorMessage = "", $errorNumber = 0) {
		try {
			if (strlen($errorMessage) > 0) {
				$this->error_desc = $errorMessage;
			} else {
				if ($this->IsConnected()) {
					$this->error_desc = mysql_error($this->mysql_link);
				} else {
					$this->error_desc = mysql_error();
				}
			}
			if ($errorNumber <> 0) {
				$this->error_number = $errorNumber;
			} else {
				if ($this->IsConnected()) {
					$this->error_number = @mysql_errno($this->mysql_link);
				} else {
					$this->error_number = @mysql_errno();
				}
			}
		} catch(Exception $e) {
			$this->error_desc = $e->getMessage();
			$this->error_number = -999;
		}
		if ($this->ThrowExceptions) {
			throw new Exception($this->error_desc);
		}
	}
	
	public function __destruct() {
	
	
	}
}
	

?>
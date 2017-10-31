<?php
	namespace Debugger;
	/**
	*
	*/
	use Debugger\mainitem;
	use Debugger\rawitem;
	use Debugger\mainitems;
	use Debugger\subitems;
	class displayfile{
		private $project;
		private $path;
		private $folder;
		private $fileName;
		public function getpath(){
			return $this->path;
		}
		public function getfolder(){
			return $this->folder;
		}
		public function getfileName(){
			return $this->fileName;
		}
		public function getproject(){
			return $this->project;
		}
		public function setpath($content){
			$this->path = $content;
		}
		public function setproject($content){
			$this->project = $content;
		}
		public function setfolder($content){
			$this->folder = $content;

		}
		public function setfileName($content){
			$this->fileName = $content;
		}
		public function prepareFileFormat(){
			$ans = array();
			$this->setpath($this->project->outputPython."/".$this->folder."/".$this->fileName);
			if (file_exists($this->path)){
				$pieces = explode("\n", file_get_contents($this->path));
				$first_raw = $pieces[0];
				$first_raw = explode(",", $first_raw);
				$values = array();
				for ($i=0;$i<sizeof($first_raw);$i++){
					$val = $first_raw[$i];
					if ($val =="algorithem" || $val =="times"){
						$obj = new mainitem($i,$val);
					}
				}
			}else{
				$ans['status'] = 555;
				$ans['message'] = "File does not exsist";
				return $ans;
			}
		}
		function __construct($folder,$file){
			$this->setfolder($folder);
			$this->setfileName($file);
		}
	}
?>
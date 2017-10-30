<?php
	namespace Debugger;
	/**
	* 
	*/
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
			$this->setpath($this->project->outputPython."/".$this->folder."/".$this->fileName);
			if (file_exists($this->path)){
				$txt = file_get_contents($this->path);
				var_dump($txt);
			}
		}
		function __construct($folder,$file){
			$this->setfolder($folder);
			$this->setfileName($file);
		}
	}
?>
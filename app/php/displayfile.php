<?php
	/**
	* 
	*/
	class displayfile
	{	
		private $project;
		private $path;
		private $fullPath
		public function getpath(){
			return $this->path;
		}
		public function getfullPath(){
			return $this->fullPath;
		}
		public function getproject(){
			return $this->project;
		}
		public function setpath($content){
			$this->path = $content;
		}
		public function setfullPath($content){
			$this->fullPath = $content;
		}
		public function setproject($content){
			$this->project = $content;
		}
		function __construct(){
			
		}
	}

?>
<?php
	namespace Debugger;
	class compress{
		private $files;
		private $folder;
		private $project;
		public function getfiles(){
			return $this->files;
		}
		public function getfolder(){
			return $this->folder;
		}
		public function getproject(){
			return $this->project;
		}
		public function setfiles($content){
			$this->files = $content;
		}
		public function setproject($content){
			$this->project = $content;
		}
		public function setfolder($content){
			$this->folder = $content;
		}
		public function compress_files(){
			chdir($this->project->outputPython);
			$archive_file_name = "out.zip";
			$zip = new \ZipArchive();
			$zip->open($archive_file_name, \ZipArchive::CREATE);
			$aa = [];
			//chdir($this->project->outputPython);
			$this->files= explode(",", $this->files);
			for ($i=0; $i < sizeof($this->files); $i++) { 
				$tmp = $this->files[$i];
				$path = $this->project->outputPython."/".$this->folder."/".$tmp;
				if (file_exists($path)){
					$cc = $zip->addFromString("out".$i.".csv",file_get_contents($path));
					$aa[$tmp] = file_get_contents($path);
				}
			}
			$zip->close();
			return (object)$aa;
		}
		function __construct($folder,$files){
			$this->setfolder($folder);
			$this->setfiles($files);
		}

	}
?>
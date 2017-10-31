<?php
	namespace Debugger;

	class rawitem
	{

		function __construct()
		{
			# code...
		}
	}
	/**
	*
	*/
	abstract class allitems
	{
		private $type;
		private $values;
		private $name;
		private $index;
		public function add_to_list($val){
			if (!in_array($val)){
				array_push($this->values,$val);
			}
		}
		public function get_list(){
			return $this->values;
		}
		public function getname(){
			return $this->name;
		}
		public function setnmae($content){
			$this->name = $content;
		}
		public function getindex(){
			return $this->index;
		}
		public function setindex($content){
			$this->index = $content;
		}
		public function gettype(){
			return $this->type;
		}
		public function settype($content){
			$this->type = $content;
		}
		function __construct($index,$type,$name){
			$this->values = array();
			$this->setindex($index);
			$this->setnmae($name);
			$this->settype($type);
		}
	}
	class mainitem extends allitems{
		function __construct($index,$name){
			parent::__construct($index,'main',$name);
		}
	}
	class subitem extends allitems{
		function __construct($index,$name){
			parent::__construct($index,'sub',$name);
		}
	}
?>
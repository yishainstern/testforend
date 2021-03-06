<?php
	namespace Debugger;

	class rawitem
	{
		private $list;
		public function getlist(){
			return $this->list;
		}
		public function getlistobj(){
			return ((object)$this->list);
		}
		public function add_to_list($key,$value){
			$this->list[$key] = $value;
		}
		function __construct(){
			$this->list = array();
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
		public function toObject(){
			$ans = array();
			$ans["type"] = $this->gettype();
			$ans["name"] = $this->getname();
			$ans["index"] = $this->getindex();
			$ans["values"] = $this->get_list();
			return ((object)$ans);
		}
		public function add_to_list($val){
			if (!in_array($val, $this->values)){
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
<?php
	function pastPom($str){
		$arr = array("\n","\r\n","\r");
		$str = str_replace($arr, "", $str);
		echo($str);
		//if (file_exists($str)) {
    		echo($str);
    		$xml = simplexml_load_file($str);
 			print_r($xml);
		//} 
	}
?>
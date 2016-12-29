<?php
	function pastPom($str){
		$arr = array("\n","\r\n","\r");
		$str = str_replace($arr, "", $str);
		echo($str);
		//if (file_exists($str)) {
    		//echo($str);
			$ff = file_get_contents ($str);
    		$xml = simplexml_load_string($ff);
    		$dom = new DOMDocument;
    		$dom->loadXML($ff);
    		$ff = $dom->getElementsByTagName('artifactId');
    		foreach ($ff as $key) {
    			if ($key->nodeValue=="maven-surefire-plugin"){
    				$rr = $key->parentNode;
    				echo $rr->tagName;
    			}
    		}
    		
    		//var_dump($ff);
    		//->resource[0]->directory;
 			//print_r($xml);
		//} 
	}
?>
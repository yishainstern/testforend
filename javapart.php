<?php
	function pastPom($str,$jar,$path){
		$arr = array("\n","\r\n","\r");
		$str = str_replace($arr, "", $str);
		
		//if (file_exists($str)) {
    		//echo($str);
			if(file_exists($str)){
				$ff = file_get_contents ($str);
    			$dom = new DOMDocument;
    			$dom->loadXML($ff);
    			$ff = $dom->getElementsByTagName('artifactId');
    			foreach ($ff as $key) {
    				if ($key->nodeValue=="maven-surefire-plugin"){
    					echo($str."\n");
    					$rr = $key->parentNode;
    					$e = $dom->createElement('argLine', "-javaagent:".$jar."=$path");
    					$rr->appendChild($e);
    					$dom->save($str);
    				}
    			}
    		}
    		
    		//var_dump($ff);
    		//->resource[0]->directory;
 			//print_r($xml);&lt;argLine&gt;-javaagent:agent.jar=C:\Downloads\airavata-
		//} 
	}
?>
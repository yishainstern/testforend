<?php
	function pastPom($str,$jar,$path){
		$arr = array("\n","\r\n","\r");
		$str = str_replace($arr, "", $str);
		//echo($str."\n");
		//if (file_exists($str)) {
    		//
			if(file_exists($str)){
                $flag = FALSE;
                //echo($str."\n");
				$ff = file_get_contents ($str);
    			$dom = new DOMDocument;
    			$dom->loadXML($ff);
    			$arrArt = $dom->getElementsByTagName('artifactId');
                //var_dump($arrArt);
                //echo(sizeof($arrArt)."\n");
    			foreach ($arrArt as $key ) {
                   //var_dump($key);
                   //echo($key->nodeValue."\n");
    				if ($key->nodeValue=="maven-surefire-plugin"){
    					$rr = $key->parentNode;
    					$confArr = $rr->childNodes;
                        //var_dump($confArr);
                        foreach ($confArr as $confElemnt ) {
                            //var_dump($confElemnt);
                            if ($confElemnt->nodeName=="configuration"){
                                echo "change ".$str."\n";
                                $e = $dom->createElement('argLine', "-javaagent:".$jar."=".$path);
                                $confElemnt->appendChild($e);
                                $flag = TRUE;
                            }
                        }
    				}
    			}
                if($flag==TRUE){
                    $dom->save($str);
                    echo "changed ".$str."\n";
                }
                
    		}
    		
    		//var_dump($ff);
    		//->resource[0]->directory;
 			//print_r($xml);&lt;argLine&gt;-javaagent:agent.jar=C:\Downloads\airavata-
		//} 
	}
?>
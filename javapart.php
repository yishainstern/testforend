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
                    $flagVersion = FALSE;
    				if ($key->nodeValue=="maven-surefire-plugin"){
    					$rr = $key->parentNode;
    					$confArr = $rr->childNodes;
                        foreach ($confArr as $confElemnt ) {
                            //var_dump($confElemnt);
                            if ($confElemnt->nodeName=="configuration"){
                                $e = $dom->createElement('argLine', "-javaagent:".$jar."=".$path);
                                $confElemnt->appendChild($e);
                                $flag = TRUE;
                            }
                            if ($confElemnt->nodeName=="version"){
                                $confElemnt->nodeValue = "2.19.1";
                                $flagVersion = TRUE;
                            }
                        }
                        if ($flagVersion == FALSE){
                            $e = $dom->createElement('version', "2.19.1");
                            $rr->appendChild($e);
                        }
    				}
    			}

                if($flag==TRUE){
                    $dom->save($str);
                    //echo "changed ".$str."\n";
                }
                
    		}
	}
?>
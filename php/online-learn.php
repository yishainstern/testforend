<?php
	//
	//updates the pom file
	function pastPom($str,$jar,$path,$files,$userProjectRoot){
		$arr = array("\n","\r\n","\r");
		$str = str_replace($arr, "", $str);
		if(file_exists($str)){
            $flag = FALSE;
			$ff = file_get_contents ($str);
    		$dom = new DOMDocument;
    		$dom->loadXML($ff);
    		$arrArt = $dom->getElementsByTagName('artifactId');
    		foreach ($arrArt as $key ) {
                $flagVersion = FALSE;
    			if ($key->nodeValue=="maven-surefire-plugin"){
    				$rr = $key->parentNode;
    				$confArr = $rr->childNodes;
                    foreach ($confArr as $confElemnt ) {
                        if ($confElemnt->nodeName=="configuration"){
                        	$was_change = $confElemnt->getElementsByTagName('argLine');
                        	if ($was_change->length>0){
                        		//echo "did it";
                        	}else{
                        		$e = $dom->createElement('argLine', "-javaagent:".$jar."=".$path);
                            	$confElemnt->appendChild($e);
                        	}
                            $tmp = $str;
                            $vowels = array($userProjectRoot);
                            $tmp = str_replace($vowels, "", $tmp);
                            $files[sizeof($files)] = $tmp;
                            $flag = TRUE;
                        }
                        /*if ($confElemnt->nodeName=="version"){
                            $confElemnt->nodeValue = "2.19.1";
                            $flagVersion = TRUE;
                        }*/
                    }
                    /*if ($flagVersion == FALSE){
                        $e = $dom->createElement('version', "2.19.1");
                        $rr->appendChild($e);
                    }*/
    			}
    		}
            if($flag==TRUE){
                $dom->save($str);
            }
    	}
    	return $files;
	}

	function run_online_task(){}
	//
	//updates the pom.xml files for using the online learning
	function update_pom_files($details_obj){
		$tmp_project = get_project_details($details_obj->folderRoot);
		$str = $details_obj->userProjectRoot."\\".$details_obj->gitName."\\".$details_obj->pomPath;
		exec("dir /s /b " .$str."\*pom.xml* > ".$details_obj->runingRoot."\\poms.txt");
		$arr = explode("\n",file_get_contents($details_obj->runingRoot."\\poms.txt"));
		$files = array();
		for ($i=0; $i < sizeof($arr) ; $i++) { 
			set_time_limit(20);
			$pathForJar = $details_obj->runingRoot."\\".$details_obj->jarName;
			$pathForPathtx = $details_obj->runingRoot."\\path.txt";
			$files = pastPom($arr[$i],$pathForJar,$pathForPathtx,$files,$details_obj->userProjectRoot);
		}
		if (sizeof($files)>0){
			$obj = json_decode(file_get_contents($details_obj->folderRoot.'\\project_details.json'));
			$obj->details->pomPath = $str;
			$obj->details->files = $files;
			file_put_contents($details_obj->folderRoot.'\\project_details.json',json_encode($obj));
			$str = "";
			$str .="cd ".$details_obj->full_pom_path."\n";
			$str .="mvn clean install -fn >".$details_obj->runingRoot."\\mavenLog.txt\n";
			run_cmd_file($details_obj,$str,"runOnline","pred");
		}else{
			$returnJson['status'] = 1;
			$returnJson['message'] = "no files with surfire plugin";
			return $returnJson;
		}
	}
	//

	function get_tags($details_obj){
		$str = $details_obj->runingRoot."\\tagList.txt";
		$str1 = $details_obj->folderRoot."\\project_details.json";
		if (is_file($str)){
			$obj = file_get_contents($str);
			$arr1 = explode("\n",$obj);
			$arr1 = array_reverse ($arr1);
			$tmp = json_decode(file_get_contents($str1));
			$tmp->details->tags = $arr1;
			file_put_contents($str1, json_encode($tmp));
			$ans = array();
			$ans['project'] = $tmp;
			$ans['status'] = 111;
			$ans['message'] = "gut tag list";
			return $ans;
		}
		
	}

	function chane_tracer_mvn_and_checkout_version($details_obj){
		$str ="";
		$str .="cd ".$details_obj->jar_creater."\r\n";
		$str .="call mvn clean install -fn >".$details_obj->runingRoot."\\create_jar_log.txt\r\n";
		$str .="cd target\r\n";
		$str .="copy ".$details_obj->jarName." ".$details_obj->runingRoot."\\".$details_obj->jarName."\r\n";	
		$str .="cd ".$details_obj->userProjectRoot."\\".$details_obj->gitName."\n";
		$str .="git checkout ".$details_obj->testVersion." 2>../../run/newVersion.txt\r\n";
		run_cmd_file($details_obj,$str,"pomrun","update_pom");
	}

	function put_path_txt($details_obj){
		$str = $details_obj->mavenroot."\r\n".$details_obj->userProjectRoot."\\".$details_obj->gitName."\r\n";
		file_put_contents($details_obj->runingRoot."\\path.txt",$str);
		chmod($details_obj->runingRoot."\\path.txt", 0777);
	}

	function move_to_online_task($details_obj){
		put_path_txt($details_obj);
		chane_tracer_mvn_and_checkout_version($details_obj);
	}
?>
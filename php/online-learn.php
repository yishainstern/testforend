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
    			if ($key->nodeValue=="maven-surefire-plugin" || $key->nodeValue=="tycho-surefire-plugin"){
    				$rr = $key->parentNode;
    				$confArr = $rr->childNodes;
    				$configuration_count = 0;
    				$flag = TRUE;
    				$e = $dom->createElement('argLine', "-javaagent:".$jar."=".$path);
                    foreach ($confArr as $confElemnt ) {
                        if ($confElemnt->nodeName=="configuration"){
                        	$configuration_count = 1;
                        	$was_change = $confElemnt->getElementsByTagName('argLine');
                            $confElemnt->appendChild($e);
                            $tmp = $str;
                            $vowels = array($userProjectRoot);
                            $tmp = str_replace($vowels, "", $tmp);
                            $files[sizeof($files)] = $tmp;
                        }
                    }
                    if ($configuration_count==0){
                    	$tmp_conf = $dom->createElement('configuration', "");
                    	$tmp_conf->appendChild($e);
                    	$rr->appendChild($tmp_conf);
                    }
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
		$tmp_str = "";
		$tmp_project = get_project_details($details_obj->folderRoot);
		$tmp_project->details->progress->mille_stones->start_testing->flag = true;
		if ($details_obj->pomPath==""){
			$str_tmp_pom_path = "";
		}else{
			$str_tmp_pom_path = "\\".$details_obj->pomPath;
		}
		$str = $details_obj->userProjectRoot."\\".$details_obj->gitName.$str_tmp_pom_path;
		if (is_dir($str)){
			exec("dir /s /b " .$str."\*pom.xml* > ".$details_obj->runingRoot."\\poms.txt");
			$arr = explode("\n",file_get_contents($details_obj->runingRoot."\\poms.txt"));
			if (is_file($str."/pom.xml")){
				$tmp_str .="cd ".$str."\n";
				$tmp_str .="call mvn clean install -fn >".$details_obj->runingRoot."\\mavenLog.txt\n";
			}
			if ((isset($arr)) && (sizeof($arr)>0)){
				$files = array();
				for ($i=0; $i < sizeof($arr) ; $i++) { 
					set_time_limit(30);
					$pathForJar = $details_obj->runingRoot."\\".$details_obj->jarName;
					$pathForPathtx = $details_obj->runingRoot."\\path.txt";
					$files = pastPom($arr[$i],$pathForJar,$pathForPathtx,$files,$details_obj->userProjectRoot);
				}
				if (sizeof($files)>0){
					$tmp_project->details->files = $files;
					
				}else{
					$tmp_project->details->problem = new stdClass();
					$tmp_project->details->problem->code = "3";
					$tmp_project->details->problem->txt = "No surefire plugin in pom files";
				}				
			}else{
				$tmp_project->details->problem = new stdClass();
				$tmp_project->details->problem->code = "2";
				$tmp_project->details->problem->txt = "There is no pom files in the version tag that you picked";
			}
		}else{
			$tmp_project->details->problem = new stdClass();
			$tmp_project->details->problem->code = "1";
			$tmp_project->details->problem->txt = "the directory does not exit in the version tag that you picked";
		}
		file_put_contents($details_obj->folderRoot.'\\project_details.json',json_encode($tmp_project));
		run_cmd_file($details_obj,$tmp_str,"runOnline","all_pred");
	}
	//

	function get_some_list($details_obj,$s1,$s2,$s3,$message,$attribute){
		$obj = json_decode(file_get_contents($s2));
		$tt = $details_obj->userProjectRoot."\\".$obj->details->gitName;
		$count = strlen($tt);
		if (is_file($s3)){
			$tmp = file_get_contents($s3);
			$ans = array();
			$ans['status'] = 111;
			$ans['message'] = $message;
			$ans[$attribute] = $tmp;
			return $ans;
		}
		if (is_file($s1)){
			$obj = file_get_contents($s1);
			$arr1 = explode("\n",$obj);
			$arr1 = array_reverse ($arr1);
			$ret_arr = array();
			for ($i=0; $i < sizeof($arr1); $i++) { 
				if($arr1[$i]=="" || $arr1[$i]==''){
					//do nothing
				}else{
					if ($attribute=="poms"){
						$sub = substr($arr1[$i], ($count+1));
						$arr1[$i] = $sub;
					}
					array_push($ret_arr, $arr1[$i]);
				}
			}
			file_put_contents($s3,json_encode($ret_arr));
			$ans = array();
			$ans['status'] = 111;
			$ans['message'] = $message;
			$ans[$attribute] = json_encode($ret_arr);
			return $ans;
		}
	}

	function get_tags($details_obj){
		$s1 = $details_obj->runingRoot."\\tagList.txt";
		$s2 = $details_obj->folderRoot."\\project_details.json";
		$s3 = $details_obj->runingRoot."\\tagList.json";
		return get_some_list($details_obj,$s1,$s2,$s3,"gut tags list","tags");
	}

	function get_poms($details_obj){
		$s1 = $details_obj->runingRoot."\\pomList.txt";
		$s2 = $details_obj->folderRoot."\\project_details.json";
		$s3 = $details_obj->runingRoot."\\pomList.json";
		return get_some_list($details_obj,$s1,$s2,$s3,"gut pom list","poms");
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
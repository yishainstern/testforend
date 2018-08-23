<?php
	//
	//Update a pom.xml file with the surefire plugin.
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
	//Get a list of all pom.xml files in the "pom root path" and update them if necessary. 
	function update_pom_files($details_obj){
		$tmp_str =  "";
		$details_obj->project = update_project_list($details_obj->project,"start_testing",true);
		if (is_dir($details_obj->project->full_pomPath)){
			exec("dir /s /b " .$details_obj->project->full_pomPath."\\*pom.xml* > ".$details_obj->project->runingRoot."\\poms.txt");
			$arr = explode("\n",file_get_contents($details_obj->project->runingRoot."\\poms.txt"));
			if ((isset($arr)) && (sizeof($arr)>0)){
				$files = array();
				for ($i=0; $i < sizeof($arr) ; $i++) { 
					set_time_limit(30);
					$pathForJar = $details_obj->project->runingRoot."\\".$details_obj->project->jarName;
					$pathForPathtx = $details_obj->project->path_online;
					$files = pastPom($arr[$i],$pathForJar,$pathForPathtx,$files,$details_obj->project->userProjectRoot);
				}
				if (sizeof($files)>0){
					$details_obj->project->pom_files = $files;
					
				}else{
					$details_obj->project->problem = true;
					$details_obj->project->problem_code = "3";
					$details_obj->project->problem_txt = "No surefire plugin in pom files";
				}				
			}else{
				$details_obj->project->problem = true;
				$details_obj->project->problem_code = "2";
				$details_obj->project->problem_txt = "There is no pom files in the version tag that you picked";
			}
		}else{
			$details_obj->project->problem = true;
			$details_obj->project->problem_code = "1";
			$details_obj->project->problem_txt = "the directory does not exit in the version tag that you picked";
		}
		$details_obj->project = update_project_list($details_obj->project,"start_testing",true);
		update_project_details($details_obj->project);
		run_cmd_file($details_obj,$details_obj->project,$details_obj->user,$tmp_str,"runOnline","all_pred");
	}
	//


	//Create a jar file from the Debbuger system and copy it into our project files.
	function chane_tracer_mvn_and_checkout_version($details_obj){
		$str ="cd ".$details_obj->project->jar_creater."\r\n";
		run_cmd_file($details_obj,$details_obj->project,$details_obj->user,$str,"pomrun","update_pom");
	}
	//Running maven for our learning requires a path.txt file here we create it.
	function put_path_txt($details_obj){
		$str = $details_obj->mavenroot."\r\n".$details_obj->project->userProjectRoot."\\".$details_obj->project->gitName."\r\n";
		file_put_contents($details_obj->project->path_online,$str);
		chmod($details_obj->project->path_online, 0777);
	}
	//After finishing "offline" part we start to execute the online part.
	function move_to_online_task($details_obj){
		#put_path_txt($details_obj);
		chane_tracer_mvn_and_checkout_version($details_obj);
	}
?>
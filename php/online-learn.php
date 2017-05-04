<?php
	//
	//updates the pom file
	function pastPom($str,$jar,$path,$returnJson,$files,$userProjectRoot){
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
                        	if (sizeof($was_change)>0){
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
	//
	//updates the pom.xml files for using the online learning
	function update_pom_files($returnJson,$pomPath,$userProjectRoot,$runingRoot,$gitName,$jarName,$folderRoot){
		if (!$tmp_project->details->progress->mille_stones->check_version->flag){
			$returnJson['status'] = 1;
			$returnJson['message'] = "can not edit before picking a test version";	
			return $returnJson;	
		}
		$str = $userProjectRoot.$gitName."\\".$pomPath;
		exec("dir /s /b " .$str."\*pom.xml* > ".$runingRoot."\\poms.txt");
		$arr = explode("\n",file_get_contents($runingRoot."\\poms.txt"));
		$files = array();
		for ($i=0; $i < sizeof($arr) ; $i++) { 
			set_time_limit(20);
			$pathForJar = $runingRoot.$jarName;
			$pathForPathtx = $runingRoot."path.txt";
			$files = pastPom($arr[$i],$pathForJar,$pathForPathtx,$returnJson,$files,$userProjectRoot);
		}
		if (sizeof($files)>0){
			$returnJson['status'] = 111;
			$returnJson['message'] = "we updated your files";	
			$obj = update_progress('update_pom', get_project_details($folderRoot),true,$folderRoot);
			$obj->details->pomPath = $str;
			file_put_contents($folderRoot.'project_details.json',json_encode($obj));
			$returnJson['project'] = $obj;
			$returnJson['data'] = $files;
			return $returnJson;
		}else{
			$returnJson['status'] = 1;
			$returnJson['message'] = "no files with surfire plugin";
			return $returnJson;
		}
	}
	//
	//create a jar file from Debbuger program with maven
	function ctrate_jar_for_online_task($returnJson,$jar_creater,$folderRoot,$runingRoot){
		$old_path = getcwd();
		chdir($jar_creater);
		set_time_limit(300);
		$command = "start /B mvn clean install -fn >".$runingRoot."\\create_jar_log.txt";
		pclose(popen($command, "w"));
		$returnJson['status'] = 111;
		$returnJson['message'] = "thank you we are creating the jar";
		$obj = update_progress('prepare_jar', get_project_details($folderRoot),TRUE,$folderRoot);
		$returnJson['project'] = $obj;
		return $returnJson;
	}
	//
	//create a file paths.txt witch contents maven repository path and the program path.
	function create_path_txt($returnJson,$userProjectRoot,$gitName,$folderRoot,$jar_test,$mavenroot,$runingRoot,$jarName){
		$str = $mavenroot."\r\n".$userProjectRoot.$gitName."\r\n";
		file_put_contents($runingRoot."path.txt",$str);
		$oldTarget = $jar_test;
		$newTarget = $runingRoot.$jarName;
		copy($oldTarget, $newTarget);
		chmod($newTarget, 0777);
		chmod($runingRoot."path.txt", 0777);
		return json_return($returnJson,0,"files in place");		
	}
	//
	//chane git pointer from "master" to a spesific versoin that was giiven by user. 
	function point_to_version($returnJson,$userProjectRoot,$gitName,$newVersion,$folderRoot,$runingRoot){
		$old_path = getcwd();
		$tmp_project = get_project_details($folderRoot);
		chdir($userProjectRoot.$gitName);
		$flag = git_tag_list($runingRoot,array($newVersion));
		if (!$flag){
			$returnJson['status'] = 1;
			$returnJson['message'] = "version does not exsist";	
			return $returnJson;	
		}
		if ($tmp_project->details->progress->mille_stones->update_pom->flag){
			$returnJson['status'] = 1;
			$returnJson['message'] = "you can not chane any more after editing pom files";	
			return $returnJson;	
		}
		//if ()
		$command = "start /B git checkout ".$newVersion." 2>../../run/newVersion.txt";
		//pclose(popen($command, "w"));
		$returnJson['status'] = 111;
		$returnJson['message'] = "checking out to version ".$newVersion." check to see if done";
		$obj = update_progress('pick_version', $tmp_project,true,$folderRoot);
		$obj->details->testVersion = $newVersion;
		file_put_contents($folderRoot.'project_details.json', json_encode($obj));
		$returnJson['project'] = $obj;
		return $returnJson;	
	}
	//
	//check if the version is the right one.
	function check_version($returnJson,$userProjectRoot,$gitName,$newVersion,$folderRoot){
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName);
		$command = "git branch >../../run/branch.txt";
		set_time_limit(700);
		exec($command);
		$str = file_get_contents("../../run/branch.txt");
		$pos = strpos($str, $newVersion);
		if ($pos==FALSE){
			$returnJson['status'] = 1;
			$returnJson['message'] = "rong version....change it agin";	
			return $returnJson;			
		}else{
			$returnJson['status'] = 111;
			$returnJson['message'] = "the current version is ".$newVersion.".";
			$obj = update_progress('check_version', get_project_details($folderRoot),TRUE,$folderRoot);
			$returnJson['project'] = $obj;
			return $returnJson;
		}
	}

	function last_preperations($returnJson,$userProjectRoot,$gitName,$folderRoot,$jar_test,$mavenroot,$runingRoot,$jarName){
		create_path_txt($returnJson,$userProjectRoot,$gitName,$folderRoot,$jar_test,$mavenroot,$runingRoot,$jarName);
		$returnJson['status'] = 111;
		$returnJson['message'] = "all done....lets go";
		$obj = update_progress('prepare_mvn', get_project_details($folderRoot),TRUE,$folderRoot);
		$returnJson['project'] = $obj;
		return $returnJson;
	}
	//
	function run_maven_task($returnJson,$userProjectRoot,$gitName,$folderRoot,$runingRoot){
		$obj = get_project_details($folderRoot);
		$path = $obj->details->pomPath;
		$old_path = getcwd();
		chdir($path);
		$command = "start /B mvn clean install -fn >".$runingRoot."mavenLog.txt";
		pclose(popen($command, "w"));
		$obj = update_progress('start_testing', get_project_details($folderRoot),TRUE,$folderRoot);
		$returnJson['project'] = $obj;
		$returnJson['status'] = 111;
		$returnJson['message'] = "starting to test...check in 20 mintes what's doing";
		return $returnJson;
	}
	//
	function maven_done($folderRoot){
		$obj = update_progress('end_testing', get_project_details($folderRoot),TRUE,$folderRoot);
		$returnJson['project'] = $obj;
		$returnJson['status'] = 111;
		$returnJson['message'] = "starting to test...check in 20 mintes what's doing";
		return $returnJson;
	}	
?>
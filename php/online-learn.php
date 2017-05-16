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
			$obj = json_decode(file_get_contents($folderRoot.'\\project_details.json'));
			$obj->details->pomPath = $str;
			$obj->details->files = $files;
			file_put_contents($folderRoot.'\\project_details.json',json_encode($obj));
			$str = "";
			$str .="cd ".$details_obj->userProjectRoot."\\".$details_obj->gitName."\\".$details_obj->pomPath."\n";
			$str .="mvn clean install -fn >".$details_obj->runingRoot."mavenLog.txt\\n";
			$str .="curl ".$details_obj->phpRoot."?own=".$details_obj->userName.",".$details_obj->folderName.",get_prediction";
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
	function change_proj($details_obj){
		$str = $details_obj->mavenroot."\r\n".$details_obj->userProjectRoot.$details_obj->gitName."\r\n";
		file_put_contents($details_obj->runingRoot."\\path.txt",$str);
		$str ="";
		$str .="cd ".$details_obj->jar_creater."\r\n";
		$str .="call mvn clean install -fn >".$details_obj->runingRoot."\\create_jar_log.txt\r\n";
		$str .="cd target\r\n";
		$str .="copy ".$details_obj->jarName." ".$details_obj->runingRoot."\\".$details_obj->jarName."\r\n";	
		$str .="cd ".$details_obj->userProjectRoot."\\".$details_obj->gitName."\r\n";
		$str .="git checkout ".$details_obj->testVersion." 2>../../run/newVersion.txt\r\n";
		file_put_contents($details_obj->runingRoot.'\\update_pom',"curl ".$details_obj->phpRoot."?own=".$details_obj->userName.",".$details_obj->folderName.",update_pom");
		file_put_contents($details_obj->runingRoot."\\dd.cmd", $str);
		chdir($details_obj->runingRoot);
		$command = "start /B dd.cmd";
		pclose(popen($command, "w"));
		//$str .="curl ".$details_obj->phpRoot."?own=".$details_obj->userName.",".$details_obj->folderName.",update_pom";
	}
	function create_path_txt($details_obj){
		$str = $details_obj->mavenroot."\r\n".$details_obj->userProjectRoot.$details_obj->gitName."\r\n";
		file_put_contents($details_obj->runingRoot."\\path.txt",$str);
		$oldTarget = $details_obj->jar_test;
		$newTarget = $details_obj->runingRoot."\\".$details_obj->jarName;
		chmod($newTarget, 0777);
		chmod($runingRoot."\\path.txt", 0777);	
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
		$command = "start /B git checkout ".$newVersion." 2>../../run/newVersion.txt";
		pclose(popen($command, "w"));
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
	function get_ready($details_obj){
		create_path_txt($details_obj);
	}
?>
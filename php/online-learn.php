<?php
	//
	//updates the pom file
	function pastPom($str,$jar,$path,$returnJson){
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
                $returnJson['data'][sizeof($returnJson['data'])]= $str;
            }
    	}
	}
	//
	//updates the pom.xml files for using the online learning
	function update_pom_files($returnJson,$userProjectRoot,$gitName,$pomPath,$relativeToUserRoot,$folderRoot,$jarName,$runingRoot){
		$str = $userProjectRoot.$gitName."\\".$pomPath;
		exec("dir /s /b " .$str."\*pom.xml* > ".$relativeToUserRoot."\\log.txt");
		$arr = explode("\n",file_get_contents($relativeToUserRoot."\\log.txt"));
		$returnJson['data'] = array();
		for ($i=0; $i < sizeof($arr) ; $i++) { 
			set_time_limit(20);
			$pathForJar = $folderRoot.$jarName;
			$pathForPathtx = $folderRoot."path.txt";
			$returnJson = pastPom($arr[$i],$pathForJar,$pathForPathtx,$returnJson);
		}
		return json_return($returnJson,0,"updated xml files in the maven system");
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
	function create_path_txt($returnJson,$mavenroot,$userProjectRoot,$gitName,$jarName,$folderRoot,$DebuugerRoot){
		$str = $mavenroot."\r\n".$userProjectRoot.$gitName."\r\n";
		file_put_contents($folderRoot."paths.txt",$str);
		$oldTarget = $DebuugerRoot.'Debugger\\my-app\\target\\'.$jarName;
		$newTarget = $folderRoot.$jarName;
		copy($oldTarget, $newTarget);
		chmod($newTarget, 0777);
		chmod($folderRoot."paths.txt", 0777);
		return json_return($returnJson,0,"files in place");		
	}
	//
	//run maven in the pgoject and wait for the folder "traces" to be created.
	function run_maven($returnJson,$userProjectRoot,$gitName,$pomPath){
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName."\\".$pomPath);
		$command = "start /B mvn clean install -fn >getLog.txt";
		pclose(popen($command, "w"));
		return json_return($returnJson,0,"maven stated....wait on hour");		
	}
	//
	//chane git pointer from "master" to a spesific versoin that was giiven by user. 
	function point_to_version($returnJson,$userProjectRoot,$gitName,$newVersion,$folderRoot,$runingRoot){
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName);
		$flag = git_tag_list($runingRoot,array($newVersion));
		if (!$flag){
			$returnJson['status'] = 1;
			$returnJson['message'] = "version does not exsist";	
			return $returnJson;	
		}
		$command = "start /B git checkout ".$newVersion." 2>../../run/newVersion.txt";
		//pclose(popen($command, "w"));
		$returnJson['status'] = 111;
		$returnJson['message'] = "checking out to version ".$newVersion." check to see if done";
		$obj = update_progress('pick_version', get_project_details($folderRoot),TRUE,$folderRoot);
		$obj->details->testVersion = $newVersion;
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
?>
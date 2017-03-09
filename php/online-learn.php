<?php
	//
	//updates the pom file
	function pastPom($str,$jar,$path){
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
                echo "changed ".$str."\n";
            }
    	}
	}
	//
	//updates the pom.xml files for using the online learning
	function update_pom_files($returnJson,$userProjectRoot,$gitName,$pomPath,$relativeToUserRoot,$folderRoot,$jarName){
		$str = $userProjectRoot.$gitName."\\".$pomPath;
		exec("dir /s /b " .$str."\*pom.xml* > ".$relativeToUserRoot."\\log.txt");
		$arr = explode("\n",file_get_contents($relativeToUserRoot."\\log.txt"));
		for ($i=0; $i < sizeof($arr) ; $i++) { 
			set_time_limit(20);
			$pathForJar = $folderRoot.$jarName;
			$pathForPathtx = $folderRoot."path.txt";
			pastPom($arr[$i],$pathForJar,$pathForPathtx);
		}
		return json_return($returnJson,0,"updated xml files in the maven system");
	}
	//
	//create a jar file from Debbuger program with maven
	function ctrate_jar_for_online_task($returnJson,$relativeToUserRoot){
		set_time_limit(300);
		$relativeToPoomRoot = $relativeToUserRoot."\\rootLearn\\Debugger\\my-app";
		exec("mvn -f ".$relativeToPoomRoot." clean install");
		return json_return($returnJson,0,"jar created");
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
		$command = "start /B mvn clean install --fail-never >getLog.txt";
		pclose(popen($command, "w"));
		return json_return($returnJson,0,"maven stated....wait on hour");		
	}
	//
	//chane git pointer from "master" to a spesific versoin that was giiven by user. 
	function point_to_version($userProjectRoot,$gitName,$newVersion){
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName);
		$command = "start /B git checkout ".$newVersion." 2>newVersion.txt";
		pclose(popen($command, "w"));
		return json_return($returnJson,0,"checking out to version ".$newVersion);	
	}
	//
	//check if the version is the right one.
	function check_version($userProjectRoot,$gitName){
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName);
		$command = "git branch 2>branch.txt";
		$checher = exec($command);
		return json_return($returnJson,0,$checher);
	}
?>
<?php
	


	function update_pom_files($returnJson,$userProjectRoot,$gitName,$pomPath,$relativeToUserRoot,$folderRoot,$jarName){
		require_once"javapart.php";
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


	function ctrate_jar_for_online_task($returnJson,$relativeToUserRoot){
		set_time_limit(300);
		$relativeToPoomRoot = $relativeToUserRoot."\\rootLearn\\Debugger\\my-app";
		exec("mvn -f ".$relativeToPoomRoot." clean install");
		return json_return($returnJson,0,"jar created");
	}

	function create_path_txt($returnJson,$mavenroot,$userProjectRoot,$gitName,$jarName,$folderRoot){
		$str = $mavenroot."\r\n".$userProjectRoot.$gitName."\r\n";
		file_put_contents($folderRoot."path.txt",$str);
		$oldTarget = $DebuugerRoot.'Debugger\\my-app\\target\\'.$jarName;
		$newTarget = $folderRoot.$jarName;
		copy($oldTarget, $newTarget);
		chmod($newTarget, 0777, true);
		chmod($folderRoot."path.txt", 0777, true);
		return json_return($returnJson,0,"files in place");		
	}

	function run_maven($returnJson,$userProjectRoot,$gitName,$pomPath){
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName."\\".$pomPath);
		$command = "start /B mvn clean install --fail-never >getLog.txt";
		pclose(popen($command, "w"));
		return json_return($returnJson,0,"maven stated....wait on hour");		
	}

	function point_to_version($userProjectRoot,$gitName,$newVersion){
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName);
		$command = "start /B git checkout ".$newVersion." 2>newVersion.txt";
		pclose(popen($command, "w"));
		return json_return($returnJson,0,"checking out to version ".$newVersion);	
	}


	function check_version($userProjectRoot,$gitName){
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName);
		$command = "git branch 2>branch.txt";
		$checher = exec($command);
		return json_return($returnJson,0,$checher);
	}

?>
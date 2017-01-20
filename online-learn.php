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
		return json_return($returnJson,0,"files in place");		
	}
?>
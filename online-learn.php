<?php
	require_once"javapart.php";


	function update_pom_files($userProjectRoot,$gitName,$pomPath,$relativeToUserRoot,$folderRoot,$jarName;){
		$str = $userProjectRoot.$gitName."\\".$pomPath;
		exec("dir /s /b " .$str."\*pom.xml* > ".$relativeToUserRoot."\\log.txt");
		$arr = explode("\n",file_get_contents($relativeToUserRoot."\\log.txt"));
		for ($i=0; $i < sizeof($arr) ; $i++) { 
			set_time_limit(20);
			$pathForJar = $folderRoot.$jarName;
			$pathForPathtx = $folderRoot."path.txt";
			pastPom($arr[$i],$pathForJar,$pathForPathtx);
		}
	}
?>
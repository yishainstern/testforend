
<?php
	require_once 'myConf.php';
	require_once 'init.php';
	require_once 'offline-learn.php';	
	header('Content-Type: application/json');
	error_reporting(E_ALL);
	$returnJson = array();
	require_once("javapart.php"); 	
	if ($task=='open folder'){
		$returnJson = start_and_prepare_folders($returnJson,$folderNmae,$relativeToUserRoot,$localUsers);
	}elseif ($task=='clone git') {
		$returnJson = clone_from_git_to_server($returnJson,$DebuugerRoot,$gitUrl,$startGit,$userProjectRoot,$gitName,$relativeToUserRoot,$amirGit);
	}elseif ($task=="check git"){
		$returnJson = check_if_clone_is_done($returnJson,$relativeToUserRoot);
	}elseif ($task=="add version") {
		$returnJson =add_bug_file_and_prepare_to_run($returnJson,$relativeToUserRoot,$fileObj,$outputPython,$all_versions,$folderRoot,$gitName);
		
	}elseif ($task=="run Python") {
		$opts = array(
  			'http'=>array(
    			'method'=>"GET",
    			'header'=>"Accept-language: en\r\n" .
              	"Cookie: foo=bar\r\n"
  			)
		);
		$context = stream_context_create($opts);
		// Open the file using the HTTP headers set above
		$file = file_get_contents($domain."/users/".$folderNmae."/index.php", false, $context);
		//echo($file);
	}elseif ($task=="run Pom") {
		$str = $userProjectRoot.$gitName."\\".$pomPath;
		exec("dir /s /b " .$str."\*pom.xml* > ".$relativeToUserRoot."\\log.txt");
		$arr = explode("\n",file_get_contents($relativeToUserRoot."\\log.txt"));
		for ($i=0; $i < sizeof($arr) ; $i++) { 
			set_time_limit(20);
			$pathForJar = $folderRoot.$jarName;
			$pathForPathtx = $folderRoot."path.txt";
			pastPom($arr[$i],$pathForJar,$pathForPathtx);
		}
	}elseif ($task=="clean mvn"){
		set_time_limit(100);
		$relativeToPoomRoot = $relativeToUserRoot."\\rootLearn\\Debugger\\my-app";
		exec("mvn -f ".$relativeToPoomRoot." clean install");
		$returnJson['status'] = 0;
		$returnJson['message'] = "maven-ready";	
		echo json_encode((object)$returnJson);	
	}elseif ($task=="mvn install"){
		//set_time_limit(100);
		//exec("mvn -f ..\\users\\".$folderNmae."\\rootLearn\\Debugger\\my-app install");
	}elseif($task=="pathTxt"){
		$str = $mavenroot."\r\n".$userProjectRoot.$gitName."\r\n";
		file_put_contents($folderRoot."path.txt",$str);
		$oldTarget = $DebuugerRoot.'Debugger\\my-app\\target\\'.$jarName;
		$newTarget = $folderRoot.$jarName;
		copy($oldTarget, $newTarget);
		$returnJson['status'] = 0;
		$returnJson['message'] = "files in place";
		echo json_encode((object)$returnJson);
	}elseif($task=="run java"){
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName."\\".$pomPath);
		$command = "start /B mvn clean install --fail-never >getLog.txt";
		echo($command);
		pclose(popen($command, "w"));		
	}elseif ($task=="chenge version"){
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName);
		$command = "start /B git checkout ".$newVersion." 2>newVersion.txt";
		pclose(popen($command, "w")); 
	}elseif ($task=="check version") {
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName);
		$command = "git branch 2>branch.txt";
		$checher = exec($command);
		echo($checher);

	}
	echo json_encode((object)$returnJson);
?>
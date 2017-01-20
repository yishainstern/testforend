
<?php
	require_once 'myConf.php';
	require_once 'init.php';
	require_once 'offline-learn.php';
	require_once 'online-learn.php';	
	header('Content-Type: application/json');
	error_reporting(E_ALL);
	$returnJson = array();
	 	
	if ($task=='open folder'){
		$returnJson = start_and_prepare_folders($returnJson,$folderNmae,$relativeToUserRoot,$localUsers);
	}elseif ($task=='clone git') {
		$returnJson = clone_from_git_to_server($returnJson,$DebuugerRoot,$gitUrl,$startGit,$userProjectRoot,$gitName,$relativeToUserRoot,$amirGit);
	}elseif ($task=="check git"){
		$returnJson = check_if_clone_is_done($returnJson,$relativeToUserRoot);
	}elseif ($task=="add version") {
		$returnJson =add_bug_file_and_prepare_to_run($returnJson,$relativeToUserRoot,$fileObj,$outputPython,$all_versions,$folderRoot,$gitName);
	}elseif ($task=="run Python") {
		$returnJson = run_python_code($domain,$folderNmae);
	}elseif ($task=="run Pom") {
		$returnJson = update_pom_files($returnJson,$userProjectRoot,$gitName,$pomPath,$relativeToUserRoot,$folderRoot,$jarName);
	}elseif ($task=="clean mvn"){
		$returnJson = ctrate_jar_for_online_task($returnJson,$relativeToUserRoot);
	}elseif($task=="pathTxt"){

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
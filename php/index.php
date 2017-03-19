
<?php
	require_once 'myConf.php';
	require_once 'init.php';
	require_once 'offline-learn.php';
	require_once 'online-learn.php';
	require_once 'list.php';	
	require_once 'new-project.php';
	header('Content-Type: false');//
	error_reporting(E_ALL);
	$returnJson = array();
	//gets the input from http post request that was givven by the user and deside to witch task to run.
	if ($task=='open_folder'){
		$returnJson = start_and_prepare_folders($folderRoot, $userProjectRoot, $DebuugerRoot, $outputPython, $runingRoot, $userNameRoot,$folderName,$discription,$bugRoot);
	}elseif ($task=='try_agin') {
		$returnJson = try_agin($returnJson,$DebuugerRoot,$gitUrl,$startGit,$userProjectRoot,$gitName,$relativeToUserRoot,$amirGit,$runingRoot,$folderRoot);
	}elseif ($task=='sgin_up') {
		$returnJson = sign_up_new_user($returnJson,$userName,$password,$userNameRoot,$first_name,$last_name);
	}elseif ($task =='get_user_list') {
		$returnJson = get_user_list($returnJson,$userName,$password,$userNameRoot);
	}elseif ($task=='get_project_progress') {
		$returnJson = get_project_progress($folderRoot);
	}elseif ($task=='log_in') {
		$returnJson = log_in($returnJson,$userName,$password,$userNameRoot);
	}elseif ($task=='clone_git') {
		$returnJson = clone_from_git_to_server($returnJson,$DebuugerRoot,$gitUrl,$startGit,$userProjectRoot,$gitName,$relativeToUserRoot,$amirGit,$runingRoot,$folderRoot);
	}elseif ($task=="check_clone"){
		$returnJson = check_if_clone_is_done($returnJson,$runingRoot,$folderRoot);
	}elseif ($task=="add_version") {
		$returnJson =add_bug_file_and_prepare_to_run($returnJson,$relativeToUserRoot,$fileObj,$outputPython,$all_versions,$folderRoot,$gitName,$userProjectRoot,$DebuugerRoot,$folderName,$runingRoot,$bugRoot);
	}elseif ($task=="run_Python") {
		$returnJson = run_python_code($folderRoot,$learn);
	}elseif ($task=="run_Pom") {
		$returnJson = update_pom_files($returnJson,$userProjectRoot,$gitName,$pomPath,$relativeToUserRoot,$folderRoot,$jarName);
	}elseif ($task=="clean_mvn"){
		$returnJson = ctrate_jar_for_online_task($returnJson,$relativeToUserRoot);
	}elseif($task=="pathTxt"){
		$returnJson = create_path_txt($returnJson,$mavenroot,$userProjectRoot,$gitName,$jarName,$folderRoot,$DebuugerRoot);
	}elseif($task=="run_java"){
		$returnJson = run_maven($returnJson,$userProjectRoot,$gitName,$pomPath);		
	}elseif ($task=="chenge_version"){
		$returnJson = point_to_version($userProjectRoot,$gitName,$newVersion);
	}elseif ($task=="check version") {
		$returnJson = check_version($userProjectRoot,$gitName);
	}
	echo json_encode((object)$returnJson);
?>
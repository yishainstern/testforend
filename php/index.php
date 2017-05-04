<?php
	require_once 'myConf.php';
	require_once 'init.php';
	require_once 'offline-learn.php';
	require_once 'online-learn.php';
	require_once 'prediction.php';
	require_once 'list.php';	
	require_once 'new-project.php';
	header('Content-Type: false');
	error_reporting(E_ALL);
	$returnJson = array();
	//gets the input from http post request that was givven by the user and deside to witch task to run.
	if ($task=='open_folder'){
		$returnJson = start_and_prepare_folders($folderRoot, $userProjectRoot, $DebuugerRoot, $outputPython, $runingRoot, $userNameRoot,$folderName,$discription,$bugRoot);
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
	}elseif ($task=='try_agin') {
		$returnJson = try_agin_to_clone($returnJson,$DebuugerRoot,$gitUrl,$startGit,$userProjectRoot,$gitName,$relativeToUserRoot,$amirGit,$runingRoot,$folderRoot);
	}elseif ($task=="check_clone"){
		$returnJson = check_if_clone_is_done($returnJson,$runingRoot,$folderRoot);
	}elseif ($task=="add_version") {
		$returnJson =add_bug_file_and_prepare_to_run($returnJson,$relativeToUserRoot,$fileObj,$outputPython,$all_versions,$folderRoot,$gitName,$userProjectRoot,$DebuugerRoot,$folderName,$runingRoot,$bugRoot);
	}elseif ($task=="run_Python") {
		$returnJson = run_python_code($folderRoot,$learn,$learnDir);
	}elseif ($task=="check_Python") {
		$returnJson = check_if_python_end($outputPython,$folderRoot);
	}elseif ($task=="update_pom") {
		$returnJson = update_pom_files($returnJson,$pomPath,$userProjectRoot,$runingRoot,$gitName,$jarName,$folderRoot);
	}elseif ($task=="create_jar"){
		$returnJson = ctrate_jar_for_online_task($returnJson,$jar_creater,$folderRoot,$runingRoot);
	}elseif($task=="pathTxt"){
		$returnJson = create_path_txt($returnJson,$mavenroot,$userProjectRoot,$gitName,$jarName,$folderRoot,$DebuugerRoot);
	}elseif($task=="run_java"){
		$returnJson = run_maven($returnJson,$userProjectRoot,$gitName,$pomPath);		
	}elseif ($task=="change_version"){
		$returnJson = point_to_version($returnJson,$userProjectRoot,$gitName,$newVersion,$folderRoot,$runingRoot);
	}elseif ($task=="check_version") {
		$returnJson = check_version($returnJson,$userProjectRoot,$gitName,$newVersion,$folderRoot);
	}elseif ($task=="last_preperations") {
		$returnJson = last_preperations($returnJson,$userProjectRoot,$gitName,$folderRoot,$jar_test,$mavenroot,$runingRoot,$jarName);
	}elseif ($task=="run_maven") {
		$returnJson = run_maven_task($returnJson,$userProjectRoot,$gitName,$folderRoot,$runingRoot);
	}elseif ($task=="maven_done") {
		$returnJson = maven_done($folderRoot);
	}elseif ($task=="prepare_pridction") {
		$returnJson = prepare_pridction($folderRoot,$userProjectRoot,$runingRoot,$outputPython);
	}elseif ($task=="run_pridction") {
		$returnJson = run_pridction($folderRoot,$learnDir);
	}elseif ($task=="get_pridction") {
		$returnJson = get_pridction($folderRoot,$learnDir);
	}

	echo json_encode((object)$returnJson);
?>
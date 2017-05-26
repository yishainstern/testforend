<?php
	require_once 'myConf.php';
	require_once 'init.php';
	require_once 'online-learn.php';
	require_once 'offline-learn.php';
	require_once 'prediction.php';
	require_once 'list.php';	
	require_once 'new-project.php';
	//header('Content-Type: false');
	error_reporting(E_ALL);
	$returnJson = array();
	
	$task = $details_obj->task;
	if ($task=='bbb'){
		file_put_contents('filename', "data");
		return;
	} 
	//gets the input from http post request that was givven by the user and deside to witch task to run.
	if ($task=='open_folder'){
		$returnJson = start_and_prepare_folders($details_obj);
	}elseif ($task=='sgin_up') {
		$returnJson = sign_up_new_user($details_obj);
	}elseif ($task =='get_user_list') {
		$returnJson = get_user_list($details_obj);
	}elseif ($task=='get_project_progress') {
		$returnJson = get_project_progress($details_obj);
	}elseif ($task=='log_in') {
		$returnJson = log_in($details_obj);
	}elseif ($task=='clone_git') {
		$returnJson = clone_from_git_to_server($details_obj);
	}elseif ($task=='try_agin') {
		$returnJson = try_agin_to_clone($details_obj);
	}elseif ($task=="check_clone"){
		$returnJson = check_if_clone_is_done($details_obj);
	}elseif ($task=="add_version") {
		$returnJson =add_bug_file_and_prepare_to_run($details_obj);
	}elseif ($task=="run_Python") {
		$returnJson = run_python_code($details_obj);
	}elseif ($task=="check_python") {
		$returnJson = check_if_python_end($details_obj);
	}elseif ($task=="update_pom") {
		$returnJson = update_pom_files($details_obj);
	}elseif ($task=="create_jar"){
		$returnJson = ctrate_jar_for_online_task($details_obj);
	}elseif($task=="pathTxt"){
		$returnJson = create_path_txt($details_obj);
	}elseif($task=="run_java"){
		$returnJson = run_maven($details_obj);		
	}elseif ($task=="change_version"){
		$returnJson = point_to_version($details_obj);
	}elseif ($task=="check_version") {
		$returnJson = check_version($details_obj);
	}elseif ($task=="last_preperations") {
		$returnJson = last_preperations($details_obj);
	}elseif ($task=="run_maven") {
		$returnJson = run_maven_task($details_obj);
	}elseif ($task=="maven_done") {
		$returnJson = maven_done($details_obj);
	}elseif ($task=="get_pridction") {
		$returnJson = get_pridction($details_obj);
	}elseif ($task=="get_tags"){
		$returnJson = get_tags($details_obj);
	}elseif ($task=="all_details"){
		$returnJson = all_details($details_obj);
	}elseif ($task=="all_pred"){
		$returnJson = all_pred($details_obj);
	}elseif ($task== "all_done") {
		//do nothing
	}elseif ($task== "get_output") {
		$returnJson = results($details_obj);
	}elseif ($task== "get_file") {
		$returnJson = get_file($details_obj);
	}

	echo json_encode((object)$returnJson);
?>
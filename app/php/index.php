<?php
	
	namespace Debugger;
	require __DIR__.'/vendor/autoload.php';
	require __DIR__.'/PHPMailer/src/Exception.php';
	require __DIR__.'/PHPMailer/src/PHPMailer.php';
	require __DIR__.'/PHPMailer/src/SMTP.php';
	require_once __DIR__.'/web/myConf.php';
	require_once __DIR__.'/web/init.php';
	require_once __DIR__.'/web/online-learn.php';
	require_once __DIR__.'/web/offline-learn.php';
	require_once __DIR__.'/web/list.php';	
	require_once __DIR__.'/web/prediction.php';
	require_once __DIR__.'/web/new-project.php';
	require_once __DIR__.'/web/new-project.php';
	//header('Content-Type: false');
	error_reporting(E_ALL);
	$returnJson = array();
	$task = $details_obj->task;
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
	}elseif ($task=="get_poms"){
		$returnJson = get_poms($details_obj);
	}elseif ($task=="all_details"){
		$returnJson = all_details($details_obj);
	}elseif ($task=="all_pred"){
		$returnJson = all_pred($details_obj);
	}elseif ($task== "all_done"){
		all_done($details_obj);
	}elseif ($task== "get_output"){
		$returnJson = results($details_obj);
	}elseif ($task== "get_watch"){
		$returnJson = get_watch($details_obj);
	}elseif ($task== "get_experiments"){
		$returnJson = experiments($details_obj);
	}elseif ($task== "get_file"){
		$returnJson = get_file($details_obj);
	}elseif ($task== "get_file_info"){
		$returnJson = get_file_info($details_obj);
	}elseif ($task== "checkout"){
		$returnJson = checkout($details_obj);
	}elseif ($task== "remove_project"){
		$returnJson = remove_project($details_obj);
	}elseif ($task== "done_remove_project"){
		$returnJson = done_remove_project($details_obj);
	}elseif ($task== "display_file"){
		$returnJson = display_file($details_obj);
	}elseif ($task== "zip_files"){
		$returnJson = zip_file($details_obj);
	}elseif ($task== "recover_account"){
		$returnJson = recover_account($details_obj);
	}elseif ($task== "change_password"){
		$returnJson = change_password($details_obj);
	}elseif ($task== "get_users_data"){
		$returnJson = get_users_data($details_obj);
	}
	$obj = json_encode((object)$returnJson);
	echo $obj;
?>
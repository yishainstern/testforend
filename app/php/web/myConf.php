<?php
	$details_obj = new stdClass();
	$details_obj->user = new stdClass();
	$details_obj->project = new stdClass();
	$details_obj->email_account = new stdClass();
	//windows server in BG|U
	//$details_obj->root = "C:\\debguer_users\\";
	//Rotem's computer
	$details_obj->root = "C:\\new_users\\";
	$details_obj->mavenroot = "C:\\Users\\amirelm\\.m2\\repository";
	$details_obj->phpRoot = "C:\xampp\htdocs\secure\in\testforend\app\php";
	$details_obj->admin_users = $details_obj->root.'\\admin_users.json';
	//email account details
	$details_obj->email_account->username = 'ddebguer@gmail.com';
	$details_obj->email_account->password = '3D3BGU3r';
	function get_all_details_of_user($details_obj){
		$arr = array();
		$user = $details_obj->user;
		$file = $details_obj->root.$user->userName.'\\user_details.json';
		$file1 = $details_obj->root.$user->userName.'\\user_server_details.json';
		$arr["user"] = new stdClass();
		$arr["details"] = new stdClass();
		if (file_exists($file)&&file_exists($file1)){
			$arr["user"] = json_decode(file_get_contents($file));
			$arr["details"] = json_decode(file_get_contents($file1));
			$arr["problem"] = false;
		}else {
			$arr["problem"] = true;
		}
		return $arr;
	}
	//get all details of project
	function get_all_details_of_project($details_obj){
		$arr = array();
		$file = $details_obj->root.$details_obj->user->userName.'\\'.$details_obj->project->folderName."\\project_details.json";
		$arr["project"] = new stdClass();
		if (file_exists($file)){
			$arr["project"] = json_decode(file_get_contents($file));
			$arr["problem"] = false;
		}else {
			$arr["problem"] = true;
		}
		return $arr;
	}
	//get all details of email account
	function get_all_details_of_email_account($details_obj){
		$arr = array();
		$arr["username"] = $details_obj->email_account->username;
		$arr["password"] = $details_obj->email_account->password;
		return $arr;
	}
	//Returns true if the given user is an adminn
	function is_admin($details_obj){
		$file = $GLOBALS['details_obj']->admin_users;
		$username = $details_obj->userName;
		$arr = json_decode(file_get_contents($file));
		return in_array($username, $arr);
	}
	//check if this php scipt is exeuted in the server.
	if (isset($argv[1]) && $argv[1]=="trigger" && sizeof($argv)==5){
		$details_obj->task = $argv[4];
		$details_obj->user->userName = $argv[2];
		$details_obj->project->folderName = $argv[3];
		$details_obj->user->userNameRoot = $details_obj->root.$details_obj->user->userName;
		$details_obj->project->folderRoot = $details_obj->user->userNameRoot.'\\'.$details_obj->project->folderName;
		$details_obj->user->user_details = $details_obj->user->userNameRoot.'\\user_details.json';
		$details_obj->user->user_server_details = $details_obj->user->userNameRoot.'\\user_server_details.json';
		$details_obj->project->project_details_file = $details_obj->project->folderRoot."\\project_details.json";
		$tmp_arr = get_all_details_of_user($details_obj);
		$details_obj->user = $tmp_arr["user"];
		$tmp_arr1 = get_all_details_of_project($details_obj);
		$details_obj->project = $tmp_arr1["project"];

	}else {
		if (isset($_POST["task"])){
			$details_obj->task = $_POST["task"];
		}else{
			$details_obj->task = '';
		}
		if (isset($_POST["gitName"])){
			$details_obj->project->gitName = $_POST["gitName"];
		}else{
			$details_obj->project->gitName = '';					
		}
		if (isset($_POST["gitUrl"])){
			$details_obj->project->gitUrl = $_POST["gitUrl"];
		}else{
			$details_obj->project->gitUrl = '';
		}
		if (isset($_POST["id"])){
			$details_obj->project->folderName = $_POST["id"];
		}else{
			$details_obj->project->folderName = '';
		}
		if (isset($_POST["userName"])){
			$details_obj->user->userName = $_POST["userName"];
		}else{
			$details_obj->user->userName = '';
		}
		if (isset($_POST["first_name"])){
			$details_obj->user->first_name = $_POST["first_name"];
		}else{
			$details_obj->user->first_name = '';
		}
		if (isset($_POST["last_name"])){
			$details_obj->user->last_name = $_POST["last_name"];
		}else{
			$details_obj->user->last_name = '';
		}	
		if (isset($_POST["user_email"])){
			$details_obj->user->user_email = $_POST["user_email"];
		}else{
			$details_obj->user->user_email = '';
		}		
		if (isset($_POST["password"])){
			$details_obj->user->password = $_POST["password"];	
		}else{
			$details_obj->user->password = '';
		}
		if (isset($_POST["agree"])){
			$details_obj->user->agree = $_POST["agree"];	
		}else{
			$details_obj->user->agree = false;
		}
		if (isset($_POST["verification"])){
			$details_obj->user->verification = $_POST["verification"];
		}
		if (isset($_POST["password_2"])){
			$details_obj->user->password_2 = $_POST["password_2"];
		}
		if (isset($_POST["testVersion"])){
			$details_obj->project->testVersion = $_POST["testVersion"];
		}else{
			$details_obj->project->testVersion = '';
		}
		if (isset($_POST["all_versions"])){
			$details_obj->project->all_versions = $_POST["all_versions"];
		}else{
			$details_obj->project->all_versions = '';
		}				 
		if (isset($_POST["pomPath"])){
			$details_obj->project->pomPath = $_POST["pomPath"];
		}else{
			$details_obj->project->pomPath = '';
		}
		if (isset($_POST["description"])){
			$details_obj->project->discription = $_POST["description"];
		}else{
			$details_obj->project->discription = '';
		}
		if (isset($_POST["issue_tracker_product_name"])){
			$details_obj->project->issue_tracker_product_name = $_POST["issue_tracker_product_name"];
		}else{
			$details_obj->project->issue_tracker_product_name = '';
		}
		if (isset($_POST["issue_tracker_url"])){
			$details_obj->project->issue_tracker_url = $_POST["issue_tracker_url"];
		}else{
			$details_obj->project->issue_tracker_url = '';
		}
		if (isset($_POST["witch_file"])){
			$details_obj->project->witch_file = $_POST["witch_file"];
		}else{
			$details_obj->project->witch_file = '';
		}
		if (isset($_POST["witch_files"])){
			$details_obj->project->witch_files = $_POST["witch_files"];
		}else{
			$details_obj->project->witch_files = '';
		}
		if (isset($_POST["witch_folder"])){
			$details_obj->project->witch_folder = $_POST["witch_folder"];
		}else{ 
			$details_obj->project->witch_folder = '';
		}
		if (isset($_POST["issue_tracker"])){
			$details_obj->project->issue_tracker = $_POST["issue_tracker"];
		}else{
			$details_obj->project->issue_tracker = '';
		}
		if (isset($_POST["file_name_output"])){
			$details_obj->project->file_name_output = $_POST["file_name_output"];
		}else{
			$details_obj->project->file_name_output = '';
		}
		if (isset($_POST["which_output"])){
			$details_obj->project->which_output = $_POST["which_output"];
		}else{
			$details_obj->project->which_output = '';
		}
	}
	if (!isset($details_obj->user->userName)){
		$details_obj->user->userName = "";
	}
	if (!isset($details_obj->project->folderName)){
		$details_obj->project->folderName = "";
	}
	if (!isset($details_obj->project->gitName)){
		$details_obj->project->gitName = "";
	}
	if (!isset($details_obj->project->pomPath)){
		$details_obj->project->pomPath = "";
	}
	
	//$details_obj->project->full_pom_path = $details_obj->project->userProjectRoot."\\".$details_obj->project->gitName."\\".$details_obj->project->pomPath;
	$details_obj->amirGit = "https://github.com/amir9979/Debugger.git";
	$details_obj->startGit = "start /B git clone --progress";
	$details_obj->startGit = "git clone --progress";
	

	
	if (isset( $_GET["own"])){
		$pieces = explode(",", $_GET["own"]);
		file_put_contents($details_obj->project->runingRoot."\\".$pieces[2]."data", "data");
	}
	//Create a cmd file to run on the server (usually for running learning task)
	function run_cmd_file($details_obj,$project,$user,$current_string,$file_name,$next_task){
		$full_name = $file_name.".cmd";
		$current_string .= "cd ".$details_obj->phpRoot."\n";
		if (strlen($next_task)>0){
			$current_string .= "php -f index.php trigger ".$user->userName." ".$project->folderName." ".$next_task." >".$project->runingRoot."\\".$file_name.".log";
		}
		file_put_contents($project->runingRoot."\\".$full_name, $current_string);
		chdir($project->runingRoot);
		$command = "start /B ".$full_name;
		pclose(popen($command, "w"));		
	}
	//Update user details
	function update_user_details($user){
		file_put_contents($user->user_details, json_encode($user));
	}
	//Update user details for the server like the session id and last time that he was connected
	function update_user_hash($details_obj,$hash){
		file_put_contents($details_obj->user_server_details, json_encode($hash));
	}
	//Update project details in server
	function update_project_details($project){
		file_put_contents($project->project_details_file,json_encode($project));
	}
	//Update project list
	function update_project_list($project,$str,$flag){
		$p_obj = $project->progress->mille_stones;
		switch ($str) {
			case 'end_clone':
				$p_obj->end_clone->flag = $flag;
				break;
			case 'start_offline':
				$p_obj->start_offline->flag = $flag;
				break;
			case 'end_offline':
				$p_obj->end_offline->flag = $flag;
				break;
			case 'start_testing':
				$p_obj->start_testing->flag = $flag;
				break;
			case 'end_testing':
				$p_obj->end_testing->flag = $flag;
				break;
			case 'run_prediction':
				$p_obj->run_prediction->flag = $flag;
				break;
			case 'get_prediction':
				$p_obj->get_prediction->flag = $flag;
				break;
		}
		return $project;
	}

?>
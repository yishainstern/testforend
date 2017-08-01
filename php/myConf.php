<?php
	$details_obj = new stdClass();
	$details_obj->user = new stdClass();
	$details_obj->project = new stdClass();
	$project = $details_obj->project;
	$user = $details_obj->user;
	//windows server in BG|U
	$details_obj->root = "C:\\DebuggerUsers\\";
	$details_obj->mavenroot = "C:\\Users\\sternyi\\.m2\\repository";
	$details_obj->phpRoot = "C:\\xampp\\htdocs\\testforend\\php";
	//
	//yishai local computer
	$details_obj->root = "C:\\Users\\pc-home\\Desktop\\Github\\users\\";
	$details_obj->mavenroot = "C:\\Users\\pc-home\\.m2\\repository";
	$details_obj->phpRoot = "C:\\Users\\pc-home\\Desktop\\Github\\mytest\\testforend\\php";
	//Get user details, session details and user details.
	function get_all_details_of_user($details_obj){
		$arr = array();
		$arr["user"] = "";
		$arr["details"] = "";
		if ($details_obj->user->user_details&&file_exists($details_obj->user->user_details)){
			$arr["user"] = json_decode(file_get_contents($details_obj->user->user_details));
			$arr["details"] = json_decode(file_get_contents($details_obj->user->user_server_details));
			$arr["problem"] = false;
		}else {
			$arr["problem"] = true;
		}
		return $arr;
	}
	//get all details of project
	function get_all_details_of_project($details_obj){
		$arr = array();
		$arr["projet"] = "";
		if ($details_obj->project->projet_details_file && file_exists($details_obj->project->projet_details_file)){
			$arr["projet"] = json_decode(file_get_contents($details_obj->projet_details_file));
			$arr["problem"] = false;
		}else {
			$arr["problem"] = true;
		}
		return $arr;
	}
	//check if this php scipt is exeuted in the server.
	if (isset($argv[1]) && $argv[1]=="trigger" && sizeof($argv)==5){
		$details_obj->task = $argv[4];
		$user->userName = $argv[2];
		$project->folderName = $argv[3];
		$user->userNameRoot = $details_obj->root.$user->userName;
		$project->folderRoot = $user->userNameRoot.'\\'.$project->folderName;
		$user->user_details = $user->userNameRoot.'\\user_details.json';
		$user->user_server_details = $user->userNameRoot.'\\user_server_details.json';
		$project->project_details_file = $details_obj->folderRoot."\\project_details.json";
		$tmp_arr = get_all_details_of_user($details_obj);
		$user = $tmp_arr["user"];
		$tmp_arr1 = get_all_details_of_project($details_obj);
		$project = $tmp_arr1["project"];
	}else {
		if (isset($_POST["task"])){
			$details_obj->task = $_POST["task"];
		}else{
			$details_obj->task = '';
		}
		if (isset($_POST["gitName"])){
			$project->gitName = $_POST["gitName"];
		}else{
			$project->gitName = '';					
		}
		if (isset($_POST["gitUrl"])){
			$project->gitUrl = $_POST["gitUrl"];
		}else{
			$project->gitUrl = '';
		}
		if (isset($_POST["id"])){
			$project->folderName = $_POST["id"];
		}else{
			$project->folderName = '';
		}
		if (isset($_POST["userName"])){
			$user->userName = $_POST["userName"];
		}else{
			$user->userName = '';
		}
		if (isset($_POST["first_name"])){
			$user->first_name = $_POST["first_name"];
		}else{
			$user->first_name = '';
		}
		if (isset($_POST["last_name"])){
			$user->last_name = $_POST["last_name"];
		}else{
			$user->last_name = '';
		}	
		if (isset($_POST["user_email"])){
			$user->user_email = $_POST["user_email"];
		}else{
			$user->user_email = '';
		}		
		if (isset($_POST["password"])){
			$user->password = $_POST["password"];	
		}else{
			$user->password = '';
		}
		if (isset($_POST["testVersion"])){
			$project->testVersion = $_POST["testVersion"];
		}else{
			$project->testVersion = '';
		}
		if (isset($_POST["all_versions"])){
			$project->all_versions = $_POST["all_versions"];
		}else{
			$project->all_versions = '';
		}				 
		if (isset($_POST["pomPath"])){
			$project->pomPath = $_POST["pomPath"];
		}else{
			$project->pomPath = '';
		}
		if (isset($_POST["description"])){
			$project->discription = $_POST["description"];
		}else{
			$project->discription = '';
		}
		if (isset($_POST["issue_tracker_product_name"])){
			$project->issue_tracker_product_name = $_POST["issue_tracker_product_name"];
		}else{
			$project->issue_tracker_product_name = '';
		}
		if (isset($_POST["issue_tracker_url"])){
			$project->issue_tracker_url = $_POST["issue_tracker_url"];
		}else{
			$project->issue_tracker_url = '';
		}
		if (isset($_POST["witch_file"])){
			$project->witch_file = $_POST["witch_file"];
		}else{
			$project->witch_file = '';
		}
		if (isset($_POST["witch_folder"])){
			$project->witch_folder = $_POST["witch_folder"];
		}else{ 
			$project->witch_folder = '';
		}
		if (isset($_POST["issue_tracker"])){
			$project->issue_tracker = $_POST["issue_tracker"];
		}else{
			$project->issue_tracker = '';
		}
		if (isset($_POST["file_name_output"])){
			$project->file_name_output = $_POST["file_name_output"];
		}else{
			$project->file_name_output = '';
		}
		if (isset($_POST["which_output"])){
			$project->which_output = $_POST["which_output"];
		}else{
			$project->which_output = '';
		}
	}
	if (!isset($user->userName)){
		$user->userName = "";
	}
	if (!isset($project->folderName)){
		$project->folderName = "";
	}
	if (!isset($project->gitName)){
		$project->gitName = "";
	}
	if (!isset($project->pomPath)){
		$project->pomPath = "";
	}
	$user->userNameRoot = $details_obj->root.$user->userName;
	$project->folderRoot = $user->userNameRoot.'\\'.$project->folderName;
	$user->user_details = $user->userNameRoot.'\\user_details.json';
	$user->user_server_details = $user->userNameRoot.'\\user_server_details.json';
	$project->project_details_file = $project->folderRoot."\\project_details.json";
	$project->userProjectRoot = $project->folderRoot.'\\rootGit';
	$project->DebuugerRoot = $project->folderRoot.'\\rootLearn';
	$project->outputPython = $project->folderRoot.'\\out';
	$project->runingRoot = $project->folderRoot.'\\run';
	$project->full_pom_path = $project->userProjectRoot."\\".$project->gitName."\\".$project->pomPath;
	$details_obj->amirGit = "https://github.com/amir9979/Debugger.git";
	$details_obj->startGit = "start /B git clone --progress";
	$details_obj->startGit = "git clone --progress";
	$details_obj->jarName = "uber-tracer-1.0.1-SNAPSHOT.jar";
	$project->learnDir = $project->DebuugerRoot.'\\Debugger\\learner';
	$project->jar_creater = $project->DebuugerRoot.'\\Debugger\\tracer';
	$details_obj->jar_test = $project->jar_creater.'\\target\\'.$details_obj->jarName;
	if (isset( $_GET["own"])){
		$pieces = explode(",", $_GET["own"]);
		file_put_contents($project->runingRoot."\\".$pieces[2]."data", "data");
	}
	//Update details of the project in server.
	function update_progress($str, $projet,$flag, $folderRoot)
	{
		if (isset($projet->details->progress->mille_stones->$str)){
			$projet->details->progress->mille_stones->$str->flag = $flag;
		}
		file_put_contents($folderRoot.'project_details.json', json_encode($projet));
		return $projet;
	}
	//Create a cmd file to run on the server (usually for running learning task)
	function run_cmd_file($details_obj,$current_string,$file_name,$next_task){
		$full_name = $file_name.".cmd";
		$current_string .= "cd ".$details_obj->phpRoot."\n";
		if (strlen($next_task)>0){
			$current_string .= "php -f index.php trigger ".$details_obj->userName." ".$details_obj->folderName." ".$next_task." >".$details_obj->runingRoot."\\".$file_name.".log";
		}
		file_put_contents($details_obj->runingRoot."\\".$full_name, $current_string);
		chdir($details_obj->runingRoot);
		$command = "start /B ".$full_name;
		pclose(popen($command, "w"));		
	}
	//Update user details
	function update_user_details($details_obj,$user){
		file_put_contents($details_obj->user->user_details, json_encode($user));
	}
	//Update user details for the server like the session id and last time that he was connected
	function update_user_hash($details_obj,$hash){
		file_put_contents($details_obj->user->user_server_details, json_encode($hash));
	}
	//Update project details
	function update_project_details($details_obj,$project){
		file_put_contents($details_obj->project->project_details_file,json_encode($project));
	}

?>
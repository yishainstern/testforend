<?php
	$details_obj = new stdClass();
	//windows server in BG|U
	$details_obj->root = "C:\\DebuggerUsers\\";
	$details_obj->mavenroot = "C:\\Users\\sternyi\\.m2\\repository";
	$details_obj->phpRoot = "C:\\xampp\\htdocs\\testforend\\php";
	//
	//yishai local computer
	$details_obj->root = "C:\\Users\\pc-home\\Desktop\\Github\\users\\";
	$details_obj->mavenroot = "C:\\Users\\pc-home\\.m2\\repository";
	$details_obj->phpRoot = "C:\\Users\\pc-home\\Desktop\\Github\\mytest\\testforend\php";
	//	
	if (isset($argv[1]) && $argv[1]=="trigger" && sizeof($argv)==5){
		$userT = json_decode(file_get_contents($details_obj->root."\\".$argv[2]."\\user_details.json"));
		$details_obj->userName = $argv[2];
		$PROJt = json_decode(file_get_contents($details_obj->root."\\".$argv[2]."\\".$argv[3]."\\project_details.json"));
		if (isset($PROJt->details->gitName)){$details_obj->gitName = $PROJt->details->gitName;}else{$details_obj->gitName = "";}
		if (isset($PROJt->details->gitUrl)){$details_obj->gitUrl = $PROJt->details->gitUrl;}else{$details_obj->gitUrl = "";}
		if (isset($PROJt->details->folderName)){$details_obj->folderName = $PROJt->details->folderName;}else{$details_obj->folderName = "";}
		if (isset($userT->details->userName)){$details_obj->userName = $userT->details->userName;}else{$details_obj->userName = "";}
		if (isset($userT->details->first_name)){$details_obj->first_name = $userT->details->first_name;}else{$details_obj->first_name = "";}
		if (isset($userT->details->last_name)){$details_obj->last_name = $userT->details->last_name;}else{$details_obj->last_name = "";}
		if (isset($userT->details->user_email)){$details_obj->user_email = $userT->details->user_email;}else{$details_obj->user_email = "";}
		if (isset($userT->details->password)){$details_obj->password = $userT->details->password;}else{$details_obj->password = "";}
		if (isset($PROJt->details->testVersion)){$details_obj->testVersion = $PROJt->details->testVersion;}else{$details_obj->testVersion = "";}
		if (isset($PROJt->details->all_versions)){$details_obj->all_versions = $PROJt->details->all_versions;}else{$details_obj->gitName = "";}
		if (isset($PROJt->details->pomPath)){$details_obj->pomPath = $PROJt->details->pomPath;}else{$details_obj->pomPath = "";}
		if (isset($PROJt->details->discription)){$details_obj->discription = $PROJt->details->discription;}else{$details_obj->discription = "";}
		if (isset($PROJt->details->issue_tracker_product_name)){$details_obj->issue_tracker_product_name = $PROJt->details->issue_tracker_product_name;}else{$details_obj->issue_tracker_product_name = "";}
		if (isset($PROJt->details->issue_tracker_url)){$details_obj->issue_tracker_url = $PROJt->details->issue_tracker_url;}else{$details_obj->issue_tracker_url = "";}
		if (isset($PROJt->details->issue_tracker)){$details_obj->issue_tracker = $PROJt->details->issue_tracker;}
		$details_obj->task = $argv[4];
	}else {
		if (isset($_POST["task"])){
			$details_obj->task = $_POST["task"];
		}else{
			$details_obj->task = '';
		}
		if (isset($_POST["gitName"])){
			$details_obj->gitName = $_POST["gitName"];
		}else{
			$details_obj->gitName = '';					
		}
		if (isset($_POST["gitUrl"])){
			$details_obj->gitUrl = $_POST["gitUrl"];
		}else{
			$details_obj->gitUrl = '';
		}
		if (isset($_POST["id"])){
			$details_obj->folderName = $_POST["id"];
		}else{
			$details_obj->folderName = '';
		}
		if (isset($_POST["userName"])){
			$details_obj->userName = $_POST["userName"];
		}else{
			$details_obj->userName = '';
		}
		if (isset($_POST["first_name"])){
			$details_obj->first_name = $_POST["first_name"];
		}else{
			$details_obj->first_name = '';
		}
		if (isset($_POST["last_name"])){
			$details_obj->last_name = $_POST["last_name"];
		}else{
			$details_obj->last_name = '';
		}	
		if (isset($_POST["user_email"])){
			$details_obj->user_email = $_POST["user_email"];
		}else{
			$details_obj->user_email = '';
		}		
		if (isset($_POST["password"])){
			$details_obj->password = $_POST["password"];	
		}else{
			$details_obj->password = '';
		}
		if (isset($_POST["testVersion"])){
			$details_obj->testVersion = $_POST["testVersion"];
		}else{
			$details_obj->testVersion = '';
		}
		if (isset($_POST["all_versions"])){
			$details_obj->all_versions = $_POST["all_versions"];
		}else{
			$details_obj->all_versions = '';
		}				 
		if (isset($_POST["pomPath"])){
			$details_obj->pomPath = $_POST["pomPath"];
		}else{
			$details_obj->pomPath = '';
		}
		if (isset($_POST["description"])){
			$details_obj->discription = $_POST["description"];
		}else{
			$details_obj->discription = '';
		}
		if (isset($_POST["issue_tracker_product_name"])){
			$details_obj->issue_tracker_product_name = $_POST["issue_tracker_product_name"];
		}else{
			$details_obj->issue_tracker_product_name = '';
		}
		if (isset($_POST["issue_tracker_url"])){
			$details_obj->issue_tracker_url = $_POST["issue_tracker_url"];
		}else{
			$details_obj->issue_tracker_url = '';
		}
		if (isset($_POST["witch_file"])){
			$details_obj->witch_file = $_POST["witch_file"];
		}else{
			$details_obj->witch_file = '';
		}
		if (isset($_POST["witch_folder"])){
			$details_obj->witch_folder = $_POST["witch_folder"];
		}else{ 
			$details_obj->witch_folder = '';
		}
		if (isset($_POST["issue_tracker"])){
			$details_obj->issue_tracker = $_POST["issue_tracker"];
		}else{
			$details_obj->issue_tracker = '';
		}
		if (isset($_POST["file_name_output"])){
			$details_obj->file_name_output = $_POST["file_name_output"];
		}else{
			$details_obj->file_name_output = '';
		}
		if (isset($_POST["which_output"])){
			$details_obj->which_output = $_POST["which_output"];
		}else{
			$details_obj->which_output = '';
		}
	}
	$details_obj->userNameRoot = $details_obj->root.$details_obj->userName;
	$details_obj->folderRoot = $details_obj->userNameRoot.'\\'.$details_obj->folderName;
	$details_obj->userProjectRoot = $details_obj->folderRoot.'\\rootGit';
	$details_obj->DebuugerRoot = $details_obj->folderRoot.'\\rootLearn';
	$details_obj->outputPython = $details_obj->folderRoot.'\\out';
	$details_obj->runingRoot = $details_obj->folderRoot.'\\run';
	$details_obj->full_pom_path = $details_obj->userProjectRoot."\\".$details_obj->gitName."\\".$details_obj->pomPath;
	$details_obj->amirGit = "https://github.com/amir9979/Debugger.git";
	$details_obj->startGit = "start /B git clone --progress";
	$details_obj->startGit = "git clone --progress";
	$details_obj->jarName = "uber-tracer-1.0.1-SNAPSHOT.jar";
	$details_obj->learnDir = $details_obj->root.$details_obj->userName.'\\'.$details_obj->folderName.'\\rootLearn\\Debugger\\learner';
	$details_obj->learn = $details_obj->DebuugerRoot.'\\Debugger\\learner';
	$details_obj->jar_creater = $details_obj->DebuugerRoot.'\\Debugger\\tracer';
	$details_obj->jar_test = $details_obj->jar_creater.'\\target\\'.$details_obj->jarName;
	if (isset( $_GET["own"])){
		$pieces = explode(",", $_GET["own"]);
		file_put_contents($details_obj->runingRoot."\\".$pieces[2]."data", "data");
	}
	if ($_FILES && $_FILES["csvFile"]){
		$details_obj->fileObj = $_FILES["csvFile"];	
	}else {
		$details_obj->fileObj = FALSE;
	}
	//Extract from the server all details of the project.
	function get_project_details($folderRoot)
	{
		$obj = json_decode(file_get_contents($folderRoot.'\\project_details.json'));
		return $obj;
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
		$current_string .= "php -f index.php trigger ".$details_obj->userName." ".$details_obj->folderName." ".$next_task." >".$details_obj->runingRoot."\\".$file_name.".log";
		file_put_contents($details_obj->runingRoot."\\".$full_name, $current_string);
		chdir($details_obj->runingRoot);
		$command = "start /B ".$full_name;
		pclose(popen($command, "w"));		
	}

?>
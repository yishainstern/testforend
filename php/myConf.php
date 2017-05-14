<?php
	$details_obj = new stdClass();
	//windows server in BG|U
	$details_obj->root = "C:\\Users\\sternyi\\Desktop\\Users\\";
	$details_obj->mavenroot = "C:\\Users\\sternyi\\.m2\\repository";
	$details_obj->phpRoot = "http://local.test/testforend/php/index.php";
	//
	//yishai local computer
	$details_obj->root = "C:\\Users\\pc-home\\Desktop\\Github\\users\\";
	$details_obj->mavenroot = "C:\\Users\\pc-home\\.m2\\repository";
	//
	
	if (isset( $_GET["own"])){
		$pieces = explode(",", $_GET["own"]);
		$userT = json_decode(file_get_contents($details_obj->root."\\".$pieces[0]."\\user_details.json"));
		$details_obj->userName = $pieces[0];
		$PROJt = json_decode(file_get_contents($details_obj->root."\\".$pieces[0]."\\".$pieces[1]."\\project_details.json"));
		if (isset($PROJt->details->gitName)){$details_obj->gitName = $PROJt->details->gitName;}
		if (isset($PROJt->details->gitUrl)){$details_obj->gitUrl = $PROJt->details->gitUrl;}
		if (isset($PROJt->details->folderName)){$details_obj->folderName = $PROJt->details->folderName;}
		if (isset( $userT->details->userName)){$details_obj->userName = $userT->details->userName;}
		if (isset($userT->details->first_name)){$details_obj->first_name = $userT->details->first_name;}
		if (isset($userT->details->last_name)){$details_obj->last_name = $userT->details->last_name;}
		if (isset($userT->details->user_email)){$details_obj->user_email = $userT->details->user_email;}
		if (isset($userT->details->password)){$details_obj->password = $userT->details->password;}
		if (isset($PROJt->details->testVersion)){$details_obj->testVersion = $PROJt->details->testVersion;}
		if (isset($PROJt->details->all_versions)){$details_obj->all_versions = $PROJt->details->all_versions;}
		if (isset($PROJt->details->pomPath)){$details_obj->pomPath = $PROJt->details->pomPath;}
		if (isset($PROJt->details->discription)){$details_obj->discription = $PROJt->details->discription;}
		$details_obj->task = $pieces[2];
		file_put_contents('ff',  $_GET["own"]);
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
		if (isset($_POST["project_description"])){
			$details_obj->discription = $_POST["project_description"];
		}else{
			$details_obj->discription = '';
		}
	}


	$details_obj->userNameRoot = $details_obj->root.$details_obj->userName;
	$details_obj->folderRoot = $details_obj->userNameRoot.'\\'.$details_obj->folderName;
	$details_obj->userProjectRoot = $details_obj->folderRoot.'\\rootGit';
	$details_obj->DebuugerRoot = $details_obj->folderRoot.'\\rootLearn';
	$details_obj->bugRoot = $details_obj->folderRoot.'\\rootBugs';
	$details_obj->outputPython = $details_obj->folderRoot.'\\out';
	$details_obj->runingRoot = $details_obj->folderRoot.'\\run'; 
	$details_obj->relativeToUserRoot = '..\\users\\'.$details_obj->folderName;
	$details_obj->localUsers = 'users\\'.$details_obj->folderName;
	$details_obj->amirGit = "https://github.com/amir9979/Debugger.git";
	$details_obj->startGit = "start /B git clone --progress";
	$details_obj->startGit = "git clone --progress";
	$details_obj->domain = "http://local.test/";
	$details_obj->jarName = "uber-tracer-1.0.1-SNAPSHOT.jar";
	$details_obj->learnDir = $details_obj->root.$details_obj->userName.'\\'.$details_obj->folderName.'\\rootLearn\\Debugger\\learner';
	$details_obj->learn = $details_obj->DebuugerRoot.'\\Debugger\\learner';
	$details_obj->jar_creater = $details_obj->DebuugerRoot.'\\Debugger\\tracer';
	$details_obj->jar_test = $details_obj->jar_creater.'\\target\\'.$details_obj->jarName;
	if ($_FILES && $_FILES["csvFile"]){
		$details_obj->fileObj = $_FILES["csvFile"];	
	}else {
		$details_obj->fileObj = FALSE;
	}

	function get_project_details($folderRoot)
	{
		$obj = json_decode(file_get_contents($folderRoot.'\\project_details.json'));
		//var_dump($obj);
		return $obj;
	}

	function update_progress($str, $projet,$flag, $folderRoot)
	{
		if (isset($projet->details->progress->mille_stones->$str)){
			$projet->details->progress->mille_stones->$str->flag = $flag;
		}
		file_put_contents($folderRoot.'project_details.json', json_encode($projet));
		return $projet;
	}

	function git_tag_list($runingRoot,$arr){
		$flag = FALSE;
		$command = 'git tag>'.$runingRoot.'\\tagList.txt';
		if (!is_file($runingRoot.'\\tagList.txt')){
			exec($command);
		}
		$str = file_get_contents($runingRoot.'\\tagList.txt');
		$arr1 = explode("\n",$str);
		$count = 0;
		for ($i=0; $i < sizeof($arr1); $i++) { 
			for ($j=0; $j < sizeof($arr); $j++) { 
				if ($arr[$j]==$arr1[$i]){
					$count++;
				}
			}
		}	
		if (sizeof($arr)==$count){
			return TRUE;
		}else {
			return FALSE;
		}	
	}

?>
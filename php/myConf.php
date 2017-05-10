<?php
	if (isset($_POST["task"])){
		$task = $_POST["task"];
	}else{
		$task = '';
	}
	if (isset($_POST["gitName"])){
		$gitName = $_POST["gitName"];
	}else{
		$gitName = '';					
	}
	if (isset($_POST["gitUrl"])){
		$gitUrl = $_POST["gitUrl"];
	}else{
		$gitUrl = '';
	}
	if (isset($_POST["id"])){
		$folderName = $_POST["id"];
	}else{
		$folderName = '';
	}
	if (isset($_POST["userName"])){
		$userName = $_POST["userName"];
	}else{
		$userName = '';
	}
	if (isset($_POST["first_name"])){
		$first_name = $_POST["first_name"];
	}else{
		$first_name = '';
	}
	if (isset($_POST["last_name"])){
		$last_name = $_POST["last_name"];
	}else{
		$last_name = '';
	}	
	if (isset($_POST["password"])){
		$password = $_POST["password"];	
	}else{
		$password = '';
	}
	if (isset($_POST["testVersion"])){
		$newVersion = $_POST["testVersion"];
	}else{
		$newVersion = '';
	}
	if (isset($_POST["ver"])){
		$all_versions = $_POST["ver"];
	}else{
		$all_versions = '';
	}				 
	if (isset($_POST["pomPath"])){
		$pomPath = $_POST["pomPath"];
	}else{
		$pomPath = '';
	}
	if (isset($_POST["project_description"])){
		$discription = $_POST["project_description"];
	}else{
		$discription = '';
	}

	//windows server in BG|U
	$root = 'C:\\Users\\sternyi\\Desktop\\Users\\';
	$mavenroot = "C:\\Users\\sternyi\\.m2\\repository";
	//
	//yishai local computer
	//$root = 'C:\\Users\\pc-home\\Desktop\\Github\\users\\';
	//$mavenroot = "C:\\Users\\pc-home\\.m2\\repository";
	//
	$userNameRoot = $root.$userName.'\\';
	$folderRoot = $userNameRoot.$folderName.'\\';
	$userProjectRoot = $folderRoot.'rootGit\\';
	$DebuugerRoot = $folderRoot.'rootLearn\\';
	$bugRoot = $folderRoot.'rootBugs\\';
	$outputPython = $folderRoot.'out';
	$runingRoot = $folderRoot.'run\\'; 
	$relativeToUserRoot = '..\\users\\'.$folderName;
	$localUsers = 'users\\'.$folderName;
	$amirGit = "https://github.com/amir9979/Debugger.git";
	$startGit = "start /B git clone --progress";
	$domain = "http://local.test/";
	$jarName = "uber-tracer-1.0.1-SNAPSHOT.jar";
	$learnDir = $root.$userName.'\\'.$folderName.'\\rootLearn\\Debugger\\learner';
	$learn = $DebuugerRoot.'Debugger\\learner';
	$jar_creater = $DebuugerRoot.'Debugger\\tracer';
	$jar_test = $jar_creater.'\\target\\'.$jarName;
	if ($_FILES && $_FILES["csvFile"]){
		$fileObj = $_FILES["csvFile"];	
	}else {
		$fileObj = FALSE;
	}

	function get_project_details($folderRoot)
	{
		$obj = json_decode(file_get_contents($folderRoot.'project_details.json'));
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
		$command = 'git tag>'.$runingRoot.'tagList.txt';
		if (!is_file($runingRoot.'tagList.txt')){
			exec($command);
		}
		$str = file_get_contents($runingRoot.'tagList.txt');
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
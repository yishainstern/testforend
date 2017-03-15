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
	$root = 'C:\\Users\\pc-home\\Desktop\\Github\\users\\';
	$userNameRoot = $root.$userName.'\\';
	$folderRoot = $userNameRoot.$folderName.'\\';
	$userProjectRoot = $folderRoot.'rootGit\\';
	$DebuugerRoot = $folderRoot.'rootLearn\\';
	$outputPython = $folderRoot.'out\\';
	$runingRoot = $folderRoot.'run\\'; 
	$relativeToUserRoot = '..\\users\\'.$folderNmae;
	$localUsers = 'users\\'.$folderNmae;
	$mavenroot = "C:\\Users\\pc-home\\.m2\\repository";
	$amirGit = "https://github.com/amir9979/Debugger.git";
	$startGit = "start /B git clone --progress";
	$domain = "http://local.test/";
	$jarName = "uber-my-app-1.0.1-SNAPSHOT.jar";
	if ($_FILES && $_FILES["csvFile"]){
		$fileObj = $_FILES["csvFile"];	
	}else {
		$fileObj = FALSE;
	}
?>
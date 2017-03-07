<?php
	$task = $_POST["task"];
	$gitName = $_POST["gitName"];
	$gitUrl = $_POST["gitName"];
	$folderNmae = $_POST["id"];
	$userNmae = $_POST["userNmae"];
	$password = $_POST["password"];
	$newVersion = $_POST["testVersion"];
	$all_versions = $_POST["ver"];
	$root = 'C:\\Users\\pc-home\\Desktop\\Github\\users\\';
	$userNmaeRoot = $root.$userNmae;
	$folderRoot = $root.$folderNmae.'\\';
	$userProjectRoot = $folderRoot.'rootGit\\';
	$DebuugerRoot = $folderRoot.'rootLearn\\';
	$outputPython = $folderRoot.'out';
	$relativeToUserRoot = '..\\users\\'.$folderNmae;
	$localUsers = 'users\\'.$folderNmae;
	$mavenroot = "C:\\Users\\pc-home\\.m2\\repository";
	$pomPath = $_POST["pomPath"];
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
<?php	

	function update_user_details($userNameRoot,$folderName,$discription){
		//echo ($userNameRoot.'user_details.json '."\n");
		$str = json_decode(file_get_contents($userNameRoot.'user_details.json'));
		//var_dump($str);
		//echo (is_array($str->list));
		$arr = $str->list;
		$count = sizeof($arr);
		$obj = new stdClass();
		$obj->name = $folderName;
		$obj->discription = $discription;
		$arr[$count] = $obj; 
		$str->list = $arr;
		file_put_contents($userNameRoot.'user_details.json',json_encode($str));
		return $str;
	}

	function update_project_details($folderName,$discription,$folderRoot){
		$obj = new stdClass();
		$obj->details = new stdClass();
		$obj->details->name = $folderName;
		$obj->details->discription = $discription;
		$obj->details->progress = array();
		$obj->details->progress[0] = (object) array('flag'=>TRUE,'name'=>'create_folders');
		$obj->details->progress[1] = (object) array('flag'=>FALSE,'name'=>'start_clone');
		$obj->details->progress[2] = (object) array('flag'=>FALSE,'name'=>'end_clone');
		$obj->details->progress[3] = (object) array('flag'=>FALSE,'name'=>'upload_bug_file');
		$obj->details->progress[4] = (object) array('flag'=>FALSE,'name'=>'start_offline');
		$obj->details->progress[5] = (object) array('flag'=>FALSE,'name'=>'end_offline');
		$obj->details->progress[6] = (object) array('flag'=>FALSE,'name'=>'pick_version');
		$obj->details->progress[7] = (object) array('flag'=>FALSE,'name'=>'prepare_jar');
		$obj->details->progress[8] = (object) array('flag'=>FALSE,'name'=>'prepare_mvn');
		$obj->details->progress[9] = (object) array('flag'=>FALSE,'name'=>'start_testing');
		$obj->details->progress[10] = (object) array('flag'=>FALSE,'name'=>'end_testing');
		$obj->details->progress[11] = (object) array('flag'=>FALSE,'name'=>'get_prediction');
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
		return $obj;
	}

	function start_and_prepare_folders($folderRoot,$userProjectRoot,$DebuugerRoot,$outputPython,$runingRoot,$userNameRoot,$folderName,$discription){
		if ((is_dir($folderRoot)==TRUE)){
			$returnJson['status'] = 1;
			$returnJson['message'] = "you have already a project with this name, pick a new name";
		}else{
			mkdir($folderRoot, 0777, true);
			mkdir($userProjectRoot, 0777, true);
			mkdir($DebuugerRoot, 0777, true);
			mkdir($outputPython, 0777, true);
			mkdir($runingRoot, 0777, true);
			mkdir($bugRoot, 0777, true);
			$user_details = update_user_details($userNameRoot,$folderName,$discription);
			$project_details = update_project_details($folderName,$discription,$folderRoot);
			$returnJson['status'] = 111;
			$returnJson['message'] = "created folders, lets clone :)";		
			$returnJson['user'] = $user_details;
			$returnJson['project'] = $project_details;
		}
		return $returnJson;
	}
	//
	//clone from github the latest versin of Debugger and the project of user
	function clone_from_git_to_server($returnJson, $DebuugerRoot, $gitUrl, $startGit, $userProjectRoot, $gitName, $relativeToUserRoot, $amirGit, $runingRoot, $folderRoot){
		pclose(popen($startGit." ".$gitUrl." ".$userProjectRoot.$gitName." 2>".$runingRoot."\\proj.log", "w"));
		pclose(popen($startGit." ".$amirGit." ".$DebuugerRoot."Debugger 2>".$runingRoot."\\Debugger.log", "w"));
		file_put_contents($runingRoot."\\goD.sh", "#!/bin/bash\n tail -n 1 Debugger.log");
		file_put_contents($runingRoot."\\goG.sh", "#!/bin/bash\n tail -n 1 proj.log");
		$obj = json_decode(file_get_contents($folderRoot.'project_details.json'));
		$obj->details->gitName = $gitName;
		$obj->details->gitUrl = $gitUrl;
		$obj->details->progress[1]->flag = TRUE;
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
		$returnJson['status'] = 111;
		$returnJson['message'] = "wait 5 minites and check clone";
		$returnJson['project'] = $obj; 
		return $returnJson;
	}
	//
	//check if clone task is done.
	function check_if_clone_is_done($returnJson,$runingRoot,$folderRoot){
		$old_path = getcwd();
		chdir($runingRoot);
		$output = shell_exec('bash goD.sh');
		$output1 = shell_exec('bash goG.sh');
		$flag1 = strpos($output, "done");
		$flag2 =strpos($output, "Checking out files: 100%");
		$flag3 = strpos($output1, "done");
		$flag4 = strpos($output1, "Checking out files: 100%");
		$flag5 = strpos($output, "Fatal");
		$flag6 = strpos($output1, "Fatal");
		$obj = json_decode(file_get_contents($folderRoot.'project_details.json'));
		if ($flag1 && $flag2 && $flag3 && $flag4){
			$obj->details->progress[2]->flag = TRUE;
			$returnJson['status'] = 111;
			$returnJson['message'] = "all cloned";
			$returnJson['project'] = $obj; 
		}else if ($flag5 || $flag6){
			$obj->details->try_agin = TRUE;
			$returnJson['status'] = 2;
			$returnJson['message'] = 'some failer in server try agin....';
			$returnJson['project'] = $obj;
		}else {
			$returnJson['status'] = 1;
			$returnJson['message'] = "did not finish cloning, check agin in 5 minites";
			$returnJson['project'] = $obj;
		}
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
		return $returnJson;		
	}

	function try_agin($returnJson,$DebuugerRoot,$gitUrl,$startGit,$userProjectRoot,$gitName,$relativeToUserRoot,$amirGit,$runingRoot,$folderRoot){
		$returnJson = clone_from_git_to_server($returnJson,$DebuugerRoot,$gitUrl,$startGit,$userProjectRoot,$gitName,$relativeToUserRoot,$amirGit,$runingRoot,$folderRoot);
	}
?>
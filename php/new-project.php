<?php	

	function update_user_details($userNameRoot,$folderName,$discription){
		$str = json_decode(file_get_contents($userNameRoot.'user_details.json'));
		$str->list[$folderName] = new stdClass();
		$str->list[$folderName]->name = $folderName;
		$str->list[$folderName]->discription = $discription;
		file_put_contents($userNameRoot.'user_details.json',json_encode($str));
	}

	function update_project_details($folderName,$discription,$folderRoot){
		$obj = new stdClass();
		$obj->details = new stdClass();
		$obj->details->name = $folderName;
		$obj->details->discription = $discription;
		$obj->details->progress = array();
		$obj->details->progress['create_folders'] = (object) array('flag'=>TRUE,'name'=>'create_folders');
		$obj->details->progress['clone'] = (object) array('flag'=>FALSE,'name'=>'clone');
		$obj->details->progress['start_offline'] = (object) array('flag'=>FALSE,'name'=>'start_offline');
		$obj->details->progress['end_offline'] = (object) array('flag'=>FALSE,'name'=>'end_offline');
		$obj->details->progress['start_testing'] = (object) array('flag'=>FALSE,'name'=>'start_testing');
		$obj->details->progress['end_testing'] = (object) array('flag'=>FALSE,'name'=>'end_testing');
		$obj->details->progress['get_prediction'] = (object) array('flag'=>FALSE,'name'=>'get_prediction');
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
	}

	function start_and_prepare_folders($folderRoot,$userProjectRoot,$DebuugerRoot,$outputPython,$runingRoot,$userNameRoot,$folderName,$discription){
		echo($folderRoot);
		if ((is_dir($folderRoot)==TRUE)){
			$returnJson['status'] = 1;
			$returnJson['message'] = "you have already a project with this name, pick a new name";
		}else{
			mkdir($folderRoot, 0777, true);
			mkdir($userProjectRoot, 0777, true);
			mkdir($DebuugerRoot, 0777, true);
			mkdir($outputPython, 0777, true);
			mkdir($runingRoot, 0777, true);
			update_user_details($userNameRoot,$folderName,$discription);
			update_project_details($folderName,$discription,$folderRoot);
			$returnJson['status'] = 0;
			$returnJson['message'] = "created folders, lets clone :)";		
		}
		return $returnJson;
	}
	//
	//clone from github the latest versin of Debugger and the project of user
	function clone_from_git_to_server($returnJson,$DebuugerRoot,$gitUrl,$startGit,$userProjectRoot,$gitName,$relativeToUserRoot,$amirGit,$runingRoot){
		pclose(popen($startGit." ".$gitUrl." ".$userProjectRoot.$gitName." 2>".$runingRoot."\\proj.log", "w"));
		pclose(popen($startGit." ".$amirGit." ".$DebuugerRoot."Debugger 2>".$runingRoot."\\Debugger.log", "w"));
		file_put_contents($runingRoot."\\goD.sh", "#!/bin/bash\n tail -n 1 Debugger.log");
		file_put_contents($runingRoot."\\goG.sh", "#!/bin/bash\n tail -n 1 proj.log");
		return json_return($returnJson,0,"wait 5 minites and check clone");
	}
	//
	//check if clone task is done.
	function check_if_clone_is_done($returnJson,$relativeToUserRoot){
		$old_path = getcwd();
		chdir($relativeToUserRoot);
		$output = shell_exec('bash goD.sh');
		$output1 = shell_exec('bash goG.sh');
		$flag1 = strpos($output, "done");
		$flag2 =strpos($output, "Checking out files: 100%");
		$flag3 = strpos($output1, "done");
		$flag4 = strpos($output1, "Checking out files: 100%");
		if ($flag1 && $flag2 && $flag3 && $flag4){
			$returnJson = json_return($returnJson,0,"all cloned");		
		}else {
			$returnJson = json_return($returnJson,1,"did not finish");
		}
		return $returnJson;		
	}
?>
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
	function get_progress_array(){
		$ans = new stdClass();
		$ans = json_decode(file_get_contents('progress.json'));
		return $ans;
	}
	function update_project_details($folderName,$discription,$folderRoot){
		$obj = new stdClass();
		$obj->details = new stdClass();
		$obj->details->name = $folderName;
		$obj->details->discription = $discription;
		$obj->details->progress = get_progress_array();
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
		return $obj;
	}
	function start_and_prepare_folders($folderRoot,$userProjectRoot,$DebuugerRoot,$outputPython,$runingRoot,$userNameRoot,$folderName,$discription,$bugRoot){
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
		$obj->details->progress->mille_stones->start_clone->flag = true;
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
		$returnJson['status'] = 111;
		$returnJson['message'] = "wait 5 minites and check clone";
		$returnJson['project'] = $obj; 
		return $returnJson;
	}
	//
	//check if clone task is done.
	function try_agin_to_clone($returnJson, $DebuugerRoot, $gitUrl, $startGit, $userProjectRoot, $gitName, $relativeToUserRoot, $amirGit, $runingRoot, $folderRoot){
		$obj = json_decode(file_get_contents($folderRoot.'project_details.json'));
		if ($obj->details->try_git_agin){
			if (file_exists($userProjectRoot.$gitName)){
				rename ($userProjectRoot.$gitName,$userProjectRoot.'by');
				pclose(popen('start /B RD /S /Q'.' '.$userProjectRoot.'by>'.$runingRoot."\\show","w"));
			}
			pclose(popen($startGit." ".$gitUrl." ".$userProjectRoot.$gitName." 2>".$runingRoot."\\proj.log", "w"));
			$obj->details->gitName = $gitName;
			$obj->details->gitUrl = $gitUrl;
		}
		if($obj->details->try_learn_agin){
			set_time_limit(400);
			exec('RD /S'.' '.$DebuugerRoot."Debugger");
			pclose(popen($startGit." ".$amirGit." ".$DebuugerRoot."Debugger 2>".$runingRoot."\\Debugger.log", "w"));
		}
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
		$returnJson['status'] = 111;
		$returnJson['message'] = "wait 5 minites and check clone";
		$returnJson['project'] = $obj; 
		return $returnJson;

	}
	function check_if_clone_is_done($returnJson,$runingRoot,$folderRoot){
		$old_path = getcwd();
		chdir($runingRoot);
		$data = file('proj.log');
		$output = $data[count($data)-1];
		$data = file('Debugger.log');
		$output1 = $data[count($data)-1];
		$flag1 = strpos($output, "done");
		$flag21 = strpos($output, "Checking out files: 100%");
		$flag22 = strpos($output, "Resolving deltas: 100%");
		$flag3 = strpos($output1, "done");
		$flag41 = strpos($output, "Checking out files: 100%");
		$flag42 = strpos($output, "Resolving deltas: 100%");
		$flag5 = strpos($output, "Fatal");
		$flag6 = strpos($output1, "Fatal");
		$flag7 = strpos($output, "fatal");
		$flag8 = strpos($output1, "fatal");
		//echo($flag5.' '.$flag6.' '.$flag7.' '.$flag8);
		//echo($output);
		//return;
		$obj = json_decode(file_get_contents($folderRoot.'project_details.json'));
		$obj->details->try_git_agin = FALSE;
		$obj->details->try_learn_agin = FALSE;
		$obj->details->try_agin = FALSE;
		if ($flag1 && ($flag21 || $flag22) && $flag3 && ($flag41 || $flag42)){
			$obj->details->progress->mille_stones->end_clone->flag = TRUE;
			$returnJson['status'] = 111;
			$returnJson['message'] = "all cloned";
			$returnJson['project'] = $obj; 
		}else if (!($flag5===FALSE) || !($flag6===FALSE) || !($flag7===FALSE) || !($flag8===FALSE)){
			$obj->details->try_agin = TRUE;
			$returnJson['status'] = 2;
			$returnJson['message'] = 'some failer in server try agin....';
			$returnJson['project'] = $obj;
			if (!($flag5===FALSE) || !($flag7===FALSE) ){
				$obj->details->try_git_agin = TRUE;
			}else{
				$obj->details->try_learn_agin = TRUE;
			}
		}else {
			$returnJson['status'] = 1;
			$returnJson['message'] = "did not finish cloning, check agin in 5 minites";
			$returnJson['project'] = $obj;
		}
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
		return $returnJson;		
	}

?>
<?php	
	function update_user_details($details_obj){
		$str = json_decode(file_get_contents($details_obj->userNameRoot.'\\user_details.json'));
		$arr = $str->list;
		$count = sizeof($arr);
		$obj = new stdClass();
		$obj->name =$details_obj->folderName;
		$obj->discription = $details_obj->discription;
		$arr[$count] = $obj; 
		$str->list = $arr;
		file_put_contents($details_obj->userNameRoot.'\\user_details.json',json_encode($str));
		return $str;
	}
	function get_progress_array(){
		$ans = new stdClass();
		$ans = json_decode(file_get_contents('progress.json'));
		return $ans;
	}
	function update_project_details($details_obj){
		$obj = new stdClass();
		$obj->details = new stdClass();
		$obj->details->folderName = $details_obj->folderName;
		$obj->details->discription = $details_obj->discription;
		$obj->details->gitUrl = $details_obj->gitUrl;
		$obj->details->gitName = $details_obj->gitName;
		$obj->details->progress = get_progress_array();
		file_put_contents($details_obj->folderRoot.'\\project_details.json',json_encode($obj));
		return $obj;
	}
	function is_git_project($details_obj){
		$flag = TRUE;
		set_time_limit(100);
		$check_git_file = $details_obj->userNameRoot."\\".$details_obj->gitName."_is_git.txt";
		file_put_contents($check_git_file, "");
		exec("git ls-remote ".$details_obj->gitUrl." 2>".$check_git_file);
		$str = file_get_contents($check_git_file);
		$place1 =strpos($str,"fatal");
		$place2 =strpos($str,"Fatal");
		if ($place2===FALSE && $place2===false && $place1===FALSE && $place1===false){
			$flag = TRUE;
		}else{
			$flag = false;
		}
		unlink($check_git_file);
		return $flag;

	}
	function start_and_prepare_folders($details_obj){
		$ans = array();
		if ((is_dir($details_obj->folderRoot)==TRUE)){
			$ans['status'] = 1;
			$ans['message'] = "you have already a project with this name, pick a new name";
		}else if(!is_git_project($details_obj)){
			$ans['status'] = 2;
			$ans['message'] = "the git that was inseretef does not exsists in git reposetories!!";
		}else{
			mkdir($details_obj->folderRoot, 0777, true);
			mkdir($details_obj->userProjectRoot, 0777, true);
			mkdir($details_obj->DebuugerRoot, 0777, true);
			mkdir($details_obj->outputPython, 0777, true);
			mkdir($details_obj->runingRoot, 0777, true);
			mkdir($details_obj->bugRoot, 0777, true);
			$filr_tmp = '';
			$filr_tmp .= "git clone --progress ".$details_obj->gitUrl." ".$details_obj->userProjectRoot."\\".$details_obj->gitName." 2>".$details_obj->runingRoot."\\proj.log\n";
			$filr_tmp .= "git clone --progress ".$details_obj->amirGit." ".$details_obj->DebuugerRoot."\\Debugger 2>".$details_obj->runingRoot."\\Debugger.log\n";
			$filr_tmp .= "cd ".$details_obj->userProjectRoot."\\".$details_obj->gitName."\n";
			$filr_tmp .= "git tag>".$details_obj->runingRoot."\\tagList.txt\n";
			$filr_tmp .= "dir /s /b *pom.xml >".$details_obj->runingRoot."\\pomList.txt\n";
			$filr_tmp .= "cd ".$details_obj->phpRoot."\n";
			$filr_tmp .= "php -f index.php trigger ".$details_obj->userName." ".$details_obj->folderName." check_clone";
			file_put_contents($details_obj->runingRoot.'\\dd.cmd', $filr_tmp);
			$user_details = update_user_details($details_obj);
			$project_details = update_project_details($details_obj);
			chdir($details_obj->runingRoot);
			$command = "start /B dd.cmd";
			pclose(popen($command, "w"));
			$ans['status'] = 111;
			$ans['message'] = "created folders, and stated to clone";		
			$ans['user'] = $user_details;
			$ans['project'] = $project_details;
		}
		return $ans;
	}
	//check if clone task is done.
	function try_agin_to_clone($returnJson, $DebuugerRoot, $gitUrl, $startGit, $userProjectRoot, $gitName, $relativeToUserRoot, $amirGit, $runingRoot, $folderRoot){
		$obj = json_decode(file_get_contents($folderRoot.'project_details.json'));
		if ($obj->details->try_git_agin){
			if (file_exists($userProjectRoot.$gitName)){
				rename ($userProjectRoot.$gitName,$userProjectRoot.'by');
				pclose(popen('start /B rmdir /s /q'.' '.$userProjectRoot.'by>'.$runingRoot."\\show","w"));
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
	function check_if_clone_is_done($details_obj){
		$ans = array();
		$old_path = getcwd();
		chdir($details_obj->runingRoot);
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
		$obj = json_decode(file_get_contents($details_obj->folderRoot."\\project_details.json"));
		$obj->details->try_git_agin = FALSE;
		$obj->details->try_learn_agin = FALSE;
		$obj->details->try_agin = FALSE;
		if ($flag1 && ($flag21 || $flag22) && $flag3 && ($flag41 || $flag42)){
			$obj->details->progress->mille_stones->end_clone->flag = true;
			$ans['status'] = 111;
			$ans['message'] = "all cloned";
			$ans['project'] = $obj; 
		}else if (!($flag5===FALSE) || !($flag6===FALSE) || !($flag7===FALSE) || !($flag8===FALSE)){
			$obj->details->try_agin = TRUE;
			$ans['status'] = 2;
			$ans['message'] = 'some failer in server try agin....';
			$ans['project'] = $obj;
			if (!($flag5===FALSE) || !($flag7===FALSE) ){
				$obj->details->try_git_agin = TRUE;
			}else{
				$obj->details->try_learn_agin = TRUE;
			}
		}else {
			$ans['status'] = 1;
			$ans['message'] = "did not finish cloning, check agin in 5 minites";
			$ans['project'] = $obj;
		}
		file_put_contents($details_obj->folderRoot.'\\project_details.json',json_encode($obj));
		return $ans;		
	}
	//
	//clone from github the latest versin of Debugger and the project of user
	function clone_from_git_to_server($details_obj){
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
?>
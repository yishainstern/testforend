<?php	
	//Update user's details after creating a new project.
	function update_user_new_project($details_obj,$user){
		$arr = $user->list;
		$count = sizeof($arr);
		$obj = new stdClass();
		$obj->name =$details_obj->project->folderName;
		$obj->discription = $details_obj->project->discription;
		$arr[$count] = $obj; 
		$user->list = $arr;
		update_user_details($details_obj,$user);
		return $user;
	}
	//Get all steps of a project.
	function get_progress_array(){
		$ans = new stdClass();
		$ans = json_decode(file_get_contents('progress.json'));
		return $ans;
	}
	//Create project file on the server.
	function create_project_details($details_obj){
		$project = new stdClass();
		$project->folderName = $details_obj->project->folderName;
		$project->discription = $details_obj->project->discription;
		$project->gitUrl = $details_obj->project->gitUrl;
		$project->gitName = $details_obj->project->gitName;
		$project->progress = get_progress_array();
		$project->problem = false;
		update_project_details($details_obj,$project);
		return $project;
	}
	//Check if the Git URL that was given by user exists in the Git repository.
	function is_git_project($details_obj){
		$flag = TRUE;
		set_time_limit(300);
		$check_git_file = $details_obj->user->userNameRoot."\\".$details_obj->project->gitName."_is_git.txt";
		file_put_contents($check_git_file, "");
		exec("git ls-remote ".$details_obj->project->gitUrl." 2>".$check_git_file);
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
	//All activities that are needed for a new project on the server.
	function start_and_prepare_folders($details_obj){
		$project =  $details_obj->project;
		$arr = check_session($details_obj);
		$ans = array();
		if ($arr["problem"]==true){
			$ans['status'] = 555;
			$ans['message'] = "Session expired or not exists.";
		}else if ((is_dir($project->folderRoot)==TRUE)){
			$ans['status'] = 1;
			$ans['message'] = "You have already a project with this name, pick a new name.";
		}else if(!is_git_project($details_obj)){
			$ans['status'] = 2;
			$ans['message'] = "The Git url that was inserted does not exist in Git repositories. Try a different url.";
		}else{
			mkdir($project->folderRoot, 0777, true);
			mkdir($project->userProjectRoot, 0777, true);
			mkdir($project->DebuugerRoot, 0777, true);
			mkdir($project->outputPython, 0777, true);
			mkdir($project->runingRoot, 0777, true);
			$filr_tmp = '';
			$filr_tmp .= "git clone --progress ".$project->gitUrl." ".$project->userProjectRoot."\\".$project->gitName." 2>".$project->runingRoot."\\proj.log\n";
			$filr_tmp .= "git clone --progress ".$details_obj->amirGit." ".$project->DebuugerRoot."\\Debugger 2>".$project->runingRoot."\\Debugger.log\n";
			$filr_tmp .= "cd ".$project->userProjectRoot."\\".$project->gitName."\n";
			$filr_tmp .= "git tag>".$project->runingRoot."\\tagList.txt\n";
			$filr_tmp .= "dir /s /b *pom.xml >".$project->runingRoot."\\pomList.txt\n";
			$filr_tmp .= "cd ".$details_obj->phpRoot."\n";
			$filr_tmp .= "php -f index.php trigger ".$details_obj->user->userName." ".$project->folderName." check_clone >".$project->runingRoot."\\check_clone.log";
			file_put_contents($project->runingRoot.'\\clone_task.cmd', $filr_tmp);
			$user_details = update_user_new_project($details_obj,$arr["user"]);
			$project_details = create_project_details($details_obj);
			chdir($project->runingRoot);
			$command = "start /B clone_task.cmd";
			pclose(popen($command, "w"));
			$ans['status'] = 111;
			$ans['message'] = "Created project, and started to clone.";		
			$ans['user'] = $user_details;
			$ans['project'] = $project_details;
		}
		return $ans;
	}
	//Did the server finish to clone the project from Git?
	function check_if_clone_is_done($details_obj){
		$p_obj = $details_obj->project;
		$ans = array();
		$old_path = getcwd();
		chdir($details_obj->project->runingRoot);
		$output = file_get_contents('proj.log');
		$output1 = file_get_contents('Debugger.log');
		$flag1 = strpos($output, "done");
		$flag11 = strpos($output, "git checkout -f HEAD");
		$flag21 = strpos($output, "Checking out files: 100%");
		$flag22 = strpos($output, "Resolving deltas: 100%");
		$flag3 = strpos($output1, "done");
		$flag41 = strpos($output, "Checking out files: 100%");
		$flag42 = strpos($output, "Resolving deltas: 100%");
		$flag5 = strpos($output, "Fatal");
		$flag6 = strpos($output1, "Fatal");
		$flag7 = strpos($output, "fatal");
		$flag8 = strpos($output1, "fatal");
		if ($flag11 && $flag3 && ($flag41 || $flag42)){
			$filr_tmp_1 = "cd ".$p_obj->userProjectRoot."\\".$p_obj->gitName."\n";
			$filr_tmp_1 .= "git checkout -f HEAD >".$p_obj->runingRoot."\\checkout.log\n";
			$filr_tmp_1 .= "git tag>".$p_obj->runingRoot."\\tagList.txt\n";
			$filr_tmp_1 .= "dir /s /b *pom.xml >".$p_obj->runingRoot."\\pomList.txt\n";
			$filr_tmp_1 .= "cd ".$details_obj->phpRoot."\n";
			$filr_tmp_1 .= "php -f index.php trigger ".$details_obj->user->userName." ".$p_obj->folderName." checkout >".$p_obj->runingRoot."\\check_clone1.log";
			file_put_contents($p_obj->runingRoot.'\\checkout.cmd', $filr_tmp_1);
			chdir($p_obj->runingRoot);
			$command = "start /B checkout.cmd";
			pclose(popen($command, "w"));
		}else if ($flag1 && ($flag21 || $flag22) && $flag3 && ($flag41 || $flag42)){
			$p_obj = update_project_list($p_obj,"end_clone",true);
			update_project_details($details_obj,$p_obj);
			$ans['status'] = 111;
			$ans['message'] = "all cloned";
			$ans['project'] = $p_obj; 
		}else if (!($flag5===FALSE) || !($flag6===FALSE) || !($flag7===FALSE) || !($flag8===FALSE)){
			$obj->details->try_agin = TRUE;
			$ans['status'] = 2;
			$ans['message'] = 'some failer in server try agin....';
			$ans['project'] = $p_obj;
		}
	}
	//When the server needs to do "git checkout" to finish downloading all the project.
	function checkout($details_obj){
		$obj = json_decode(file_get_contents($details_obj->folderRoot."\\project_details.json"));
		$obj->details->progress->mille_stones->end_clone->flag = true;
		file_put_contents($details_obj->folderRoot.'\\project_details.json',json_encode($obj));
	}

	function get_some_list($project,$attribute,$message){
		$json = $project->runingRoot."\\".$attribute.".json";
		$txt = $project->runingRoot."\\".$attribute.".txt";
		$tt = $project->userProjectRoot."\\".$project->gitName;
		//echo($json);
		$count = strlen($tt);
		if (is_file($json)){
			$tmp = file_get_contents($json);
			return $tmp;
		}else if (is_file($txt)){
			$obj = file_get_contents($txt);
			$arr1 = explode("\n",$obj);
			//$arr1 = array_reverse ($arr1);
			$ret_arr = array();
			for ($i=0; $i < sizeof($arr1); $i++) { 
				if($arr1[$i]=="" || $arr1[$i]==''){
					//do nothing
				}else{
					if ($attribute=="poms"){
						$sub = substr($arr1[$i], ($count+1));
						$arr1[$i] = $sub;
					}
					array_push($ret_arr, $arr1[$i]);
				}
			}
			$ret_j = json_encode($ret_arr);
			file_put_contents($json,$ret_j);
			return $ret_j;
		}else {
			return array();
		}
	}

	function get_tags($details_obj){
		$arr = check_session($details_obj);
		$ans = array();
		if ($arr["problem"]==true){
			$ans['status'] = 555;
			$ans['message'] = "Session expired or not exists.";
		}else{
			$obj = get_all_details_of_project($details_obj);
			if ($obj["problem"] == true	){
				$ans['status'] = 555;
				$ans['message'] = "Project doe's not exsit.";
			}else {
				$tmp_p = $obj["project"];
				$ans['status'] = 111;
				$ans["array"] = get_some_list($tmp_p,"tagList","Get tags list");
			}
		}
		return $ans;
	}
	//
	function get_poms($details_obj){
		/*$s1 = $details_obj->runingRoot."\\pomList.txt";
		$s2 = $details_obj->folderRoot."\\project_details.json";
		$s3 = $details_obj->runingRoot."\\pomList.json";
		return get_some_list($tmp_p,"poms","Get poms list");*/
	}	

?>
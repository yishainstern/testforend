<?php 
	
	//
	//Check if the preiction learning part is done.
	function check_if_python_end($details_obj){
		if (file_exists($details_obj->project->outputPython.'\\markers\\all_done')){
			$details_obj->project = update_project_list($details_obj->project,"end_offline",true);
			update_project_details($details_obj->project);
			move_to_online_task($details_obj);
		}else {
			$err_f = $details_obj->project->outputPython.'\\markers\\error_file';
			if (file_exists($err_f)){
				$details_obj->project->problem = true;
				update_project_details($details_obj->project);
			}else {
				//nothing
			}
		}
	}
	//One requirement of the prediction part is a configuration file in the current folder' this functions creates the folder for this task.
	function creat_conf_for_offline($project){
        $str = 'workingDir='.$project->outputPython."\r\n";
        $str = $str.'git='.$project->userProjectRoot."\\".$project->gitName."\r\n";
        $str = $str.'issue_tracker_product_name='.$project->issue_tracker_product_name."\r\n";
        $str = $str.'issue_tracker_url='.$project->issue_tracker_url."\r\n";
        $str = $str.'issue_tracker='.$project->issue_tracker."\r\n";
        $str = $str."vers=(". $project->all_versions.")";
        file_put_contents($project->folderRoot."\\configuration.txt",$str); 
	}
	//Execute the python command to start the task.
	function go_run_python($details_obj,$project,$user){
		$str = "cd ".$project->learnDir."\n";
		$conf_path = $project->folderRoot."\\configuration.txt";
		$str .= "python wrapper.py ".$conf_path." 2>".$project->runingRoot."\\offlineLogger.log\n";
		run_cmd_file($details_obj,$project,$user,$str,"offline","check_python");
	}
	//Update in the project details all of the relevant information of the project that we got from the server.
	function updates($details_obj,$project){
		$project->all_versions = $details_obj->project->all_versions;
		$project->testVersion = $details_obj->project->testVersion;
		$project->pomPath = $details_obj->project->pomPath;
		if ($project->pomPath==""){
			$str_tmp_pom_path = "";
		}else{
			$str_tmp_pom_path = "\\".$project->pomPath;
		}
		$project->full_pomPath = $project->userProjectRoot."\\".$project->gitName.$str_tmp_pom_path;
		$project->issue_tracker_product_name = $details_obj->project->issue_tracker_product_name;
		$project->issue_tracker_url = $details_obj->project->issue_tracker_url;
		$project->issue_tracker = $details_obj->project->issue_tracker;
		$project->path_online = $project->runingRoot."\\path.txt";
		$project = update_project_list($project,"start_offline",true);
		update_project_details($project);
		return $project;		
	}
	//
	function all_details($details_obj){
		$arr = check_session($details_obj);
		$ans = array();
		if ($arr["problem"]==true){
			$ans['status'] = 555;
			$ans['message'] = "Session expired or not exists.";
		}else{
			$obj = get_all_details_of_project($details_obj);
			$obj_1 = updates($details_obj,$obj["project"]);
			creat_conf_for_offline($obj_1);
			go_run_python($details_obj,$obj_1,$arr["user"]);
			$ans['status'] = 111;
			$ans['message'] = "offline task started";
			$ans['project'] = $obj_1;
		}
		return $ans;
	}
?>
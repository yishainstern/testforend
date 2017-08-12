<?php 
	//
	//issue_tracker_product_name  issue_tracker_url issue_tracker
	
	function update_details($folderRoot,$fileObj,$all_versions){
		$obj = json_decode(file_get_contents($folderRoot.'project_details.json'));
		$obj->details->progress->mille_stones->upload_bug_file->flag = TRUE;
		if ($fileObj["name"]){
			$obj->details->bugFileName = $fileObj["name"];
		}
		$obj->details->versions = $all_versions;
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
		return $obj;
	}
	//
	//check if offline task is over
	function check_if_python_end($details_obj){
		if (file_exists($details_obj->project->outputPython.'\\markers\\learner_phase_file')){
			$project = update_project_list($details_obj->project,"end_offline",true);
			update_project_details($details_obj->project);
			move_to_online_task($details_obj);
		}else {
			
			//run_cmd_file($details_obj,"","offline","check_python");
		}
	}
	//issue_tracker_product_name  issue_tracker_url issue_tracker
	function creat_conf_for_offline($project){
		var_dump($project);
        $str = 'workingDir='.$project->outputPython."\r\n";
        $str = $str.'git='.$project->userProjectRoot."\\".$project->gitName."\r\n";
        $str = $str.'issue_tracker_product_name='.$project->issue_tracker_product_name."\r\n";
        $str = $str.'issue_tracker_url='.$project->issue_tracker_url."\r\n";
        $str = $str.'issue_tracker='.$project->issue_tracker."\r\n";
        $str = $str."vers=(". $project->all_versions.")";
        file_put_contents($project->learnDir."\\antConf.txt",$str); 
	}
	function go_run_python($details_obj,$project,$user){
		$str = "cd ".$project->learnDir."\n";
		$str .= "python wrapper.py antConf.txt learn 2>offlineLogger.log\n";
		run_cmd_file($details_obj,$project,$user,$str,"offline","check_python");
	}
	function updates($details_obj,$project){
		$project->all_versions = $details_obj->project->all_versions;
		$project->testVersion = $details_obj->project->testVersion;
		$project->pomPath = $details_obj->project->pomPath;
		$project->full_pomPath = $project->userProjectRoot."\\".$project->gitName."\\".$project->pomPath;
		$project->issue_tracker_product_name = $details_obj->project->issue_tracker_product_name;
		$project->issue_tracker_url = $details_obj->project->issue_tracker_url;
		$project->issue_tracker = $details_obj->project->issue_tracker;
		$project = update_project_list($project,"start_offline",true);
		update_project_details($project);
		return $project;		
	}

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
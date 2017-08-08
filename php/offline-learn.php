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
	//use http get request to run the python project.
	function run_python_code($folderRoot,$learn,$learnDir){
		chdir($learnDir);
		$command = 'start /B python wrapper.py antConf.txt learn 2>ff.log';
		pclose(popen($command, "w"));
		$obj = json_decode(file_get_contents($folderRoot.'project_details.json'));
		$obj->details->progress->mille_stones->start_offline->flag = true;
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
		$returnJson['status'] = 111;
		$returnJson['message'] = "strted...check later";
		$returnJson['project'] = $obj;
		return $returnJson;
	}
	//check if offline task is over
	function check_if_python_end($details_obj){
		$obj = json_decode(file_get_contents($details_obj->folderRoot."\\project_details.json"));
		$ans = array();
		if (file_exists($details_obj->outputPython.'\\markers\\learner_phase_file')){
			$obj->details->progress->mille_stones->end_offline->flag = true;
			file_put_contents($details_obj->folderRoot."\\project_details.json",json_encode($obj));
			move_to_online_task($details_obj);
		}else {
			$a_file = $details_obj->outputPython.'\\markers\\issue_tracker_file';
			if (file_exists($a_file)){
				$a_txt = file_get_contents($a_file);
				if ($a_txt=="failed"){
					$obj->details->problem = new stdClass();
					$obj->details->problem->code = "3";
					$obj->details->problem->txt = "bad issue tracker name or url";
					file_put_contents($details_obj->folderRoot."\\project_details.json",json_encode($obj));
				}
			}
			//run_cmd_file($details_obj,"","offline","check_python");
		}
	}
	

	//issue_tracker_product_name  issue_tracker_url issue_tracker
	function creat_conf_for_offline($details_obj){
        $str = 'workingDir='.$details_obj->outputPython."\r\n";
        $str = $str.'git='.$details_obj->userProjectRoot."\\".$details_obj->gitName."\r\n";
        $str = $str.'issue_tracker_product_name='.$details_obj->issue_tracker_product_name."\r\n";
        $str = $str.'issue_tracker_url='.$details_obj->issue_tracker_url."\r\n";
        $str = $str.'issue_tracker='.$details_obj->issue_tracker."\r\n";
        $str = $str."vers=(". $details_obj->all_versions.")";
        file_put_contents($details_obj->learnDir."\\antConf.txt",$str); 
	}
	function go_run_python($details_obj){
		$str = "cd ".$details_obj->learnDir."\n";
		$str .= "python wrapper.py antConf.txt learn 2>offlineLogger.log\n";
		run_cmd_file($details_obj,$str,"offline","check_python");
	}
	function updates($details_obj,$project){
		$project->all_versions = $details_obj->project->all_versions;
		$project->testVersion = $details_obj->project->testVersion;
		$project->pomPath = $details_obj->project->pomPath;
		$project->full_pomPath = $details_obj->project->userProjectRoot."\\".$project->gitName."\\".$project->pomPath;
		$project->issue_tracker_product_name = $details_obj->project->issue_tracker_product_name;
		$project->issue_tracker_url = $details_obj->project->issue_tracker_url;
		$project->issue_tracker = $details_obj->project->issue_tracker;
		$project = update_project_list($project,"start_offline",true);
		update_project_details($details_obj,$project);
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
			creat_conf_for_offline($details_obj,$obj);
			go_run_python($obj_1);
			$ans['status'] = 111;
			$ans['message'] = "offline task started";
			$ans['project'] = $obj_1;
		}
		return $ans;
	}
?>
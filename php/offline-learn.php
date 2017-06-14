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
	//get a scv file from user than contains a bug list in a spesific format.
	function add_bug_file_and_prepare_to_run($returnJson,$relativeToUserRoot,$fileObj,$outputPython,$all_versions,$folderRoot,$gitName,$userProjectRoot,$DebuugerRoot,$folderNmae,$runingRoot,$bugRoot){
		if ($fileObj&&$fileObj["error"]==UPLOAD_ERR_OK&& $fileObj["tmp_name"]){
			$name = put_bug_file_in_place($fileObj,$folderRoot,$bugRoot);
			creat_conf_for_offline($outputPython,$userProjectRoot,$gitName,$name,$all_versions,$folderRoot,$DebuugerRoot);
			//prepare_runing_file_for_offline_task($folderNmae);
			$project_details = update_details($folderRoot,$fileObj,$all_versions);
			$returnJson['status'] = 111;
			$returnJson['message'] = "all ready for offline task:)";
			$returnJson['project'] = $project_details;
		}else {
			$returnJson = json_return($returnJson,1,"somthing went rong...try again");
		}
		return $returnJson;
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
	function any_prob_offline($details_obj){
		return false;
	}
	//put the scv file in the right place.
	function put_bug_file_in_place($details_obj,$obj){
		$tmp_name = $details_obj->fileObj["tmp_name"];
        $name = basename($details_obj->fileObj["name"]);
        $uploadDir = $details_obj->folderRoot."\\rootBugs";
        move_uploaded_file($tmp_name, $details_obj->bugRoot."\\".$name);
        chmod($uploadDir."\\".$name, 0777);
	}
	function updates($details_obj){
		$a_file = $details_obj->outputPython.'\\markers\\issue_tracker_file';
		if (is_file($a_file)){
			file_put_contents($a_file,"strat agin");
		}
		$obj = json_decode(file_get_contents($details_obj->folderRoot.'\\project_details.json'));
		$obj->details->problem = new stdClass();
		$obj->details->problem->code = "0";
		$obj->details->all_versions = $details_obj->all_versions;
		$obj->details->testVersion = $details_obj->testVersion;
		$obj->details->pomPath = $details_obj->pomPath;
		$obj->details->full_pomPath = $details_obj->userProjectRoot."\\".$details_obj->gitName."\\".$details_obj->pomPath;
		$obj->details->issue_tracker_product_name = $details_obj->issue_tracker_product_name;
		$obj->details->issue_tracker_url = $details_obj->issue_tracker_url;
		$obj->details->issue_tracker = $details_obj->issue_tracker;
		$obj->details->progress->mille_stones->start_offline->flag = true;
		file_put_contents($details_obj->folderRoot.'\\project_details.json', json_encode($obj));
		return $obj;		
	}
	//issue_tracker_product_name  issue_tracker_url issue_tracker
	function creat_conf_for_offline($details_obj,$obj){
        $str = 'workingDir='.$details_obj->outputPython."\r\n";
        $str = $str.'git='.$details_obj->userProjectRoot."\\".$details_obj->gitName."\r\n";
        $str = $str.'issue_tracker_product_name='.$details_obj->issue_tracker_product_name."\r\n";
        $str = $str.'issue_tracker_url='.$details_obj->issue_tracker_url."\r\n";
        $str = $str.'issue_tracker='.$details_obj->issue_tracker."\r\n";
        $str = $str."vers=(". $details_obj->all_versions.")";
        file_put_contents($details_obj->DebuugerRoot."\\Debugger\\learner\\antConf.txt",$str); 
	}
	function go_run_python($details_obj){
		$str = "cd ".$details_obj->learnDir."\n";
		$str .= "python wrapper.py antConf.txt learn 2>offlineLogger.log\n";
		run_cmd_file($details_obj,$str,"offline","check_python");
	}
	function all_details($details_obj){
		$ans = array();
		if (any_prob_offline($details_obj)){
			return;
		}
		$obj = updates($details_obj);
		//put_bug_file_in_place($details_obj,$obj);
		creat_conf_for_offline($details_obj,$obj);
		go_run_python($details_obj);
		$ans['status'] = 111;
		$ans['message'] = "offline task started";
		$ans['project'] = $obj;
		return $ans;
	}
?>
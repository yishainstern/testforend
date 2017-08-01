<?php
	//Check the session of user
	function check_session($details_obj){
		$arr = array();
		session_start();
		$r = session_id();
		$time = time();
		$arr = get_all_details_of_user($details_obj);
		if ($arr["problem"]==true){
			$arr["flag"] = false;
		}else{
			$int = (int)$arr["details"]->session_time;
			if (($arr["details"]->session_id==$r)&&($time-$int < (60*60*30))){
				$arr["details"]->session_time = "".$time;
				update_user_hash($details_obj,$arr["details"]);
				$arr["flag"] = true;
			}else{
				$arr["flag"] = false;
			}
		}
		return $arr;
	}
	//
	function get_user_list($details_obj){
		$arr = check_session($details_obj);
		$ans = array();
		if ($arr["flag"] == false){
			$ans['status'] = 555;
			$ans['message'] = "Session expired or not exists.";
		}else {
			$ans['user'] = $arr["user"]; 
			$ans['status'] = 111;
			$ans['message'] = "Get user details";
		}
		return $ans;
	}

	function get_project_progress($details_obj){
		$obj = json_decode(file_get_contents($details_obj->folderRoot.'\\project_details.json'));
		$a_file = $details_obj->outputPython.'\\markers\\issue_tracker_file';
		if (file_exists($a_file)){
			$a_txt = file_get_contents($a_file);
			$a_flag = strpos($a_txt, "failed");
			if (!$a_flag===FALSE){
				$obj->details->problem = new stdClass();
				$obj->details->problem->code = "3";
				$obj->details->problem->txt = "bad issue tracker name or url";
				$obj->details->progress->mille_stones->start_offline->flag = false;
				file_put_contents($details_obj->folderRoot."\\project_details.json",json_encode($obj));
			}
		}		
		$returnJson['project'] = $obj; 
		$returnJson['status'] = 111;
		$returnJson['message'] = "got the progress";	
		return $returnJson;	
	}


	function remove_project($details_obj){
		$arr = check_session($details_obj);
		$ans = array();
		if ($arr["flag"] == false){
			$ans['status'] = 555;
			$ans['message'] = "Session expired or not exists.";
		}else {
			if ($arr["details"]->start_remove == true){
				$ans['status'] = 444;
				$ans['message'] = "Still removing old project.";
			}else {
				$arr["details"]->start_remove = true;
				update_user_hash($details_obj,$arr["details"]);
				$str = "cd ".$details_obj->userNameRoot."\n";
				$str .= "echo start>rm.txt";
				$str .= "cd ".$details_obj->folderRoot."\n";
				$str .= "del /Q /S *\n";
				$str .= "del /Q /S *\n";
				$str .= "cd ".$details_obj->userNameRoot."\n";
				$str .="rename";
				$str .= "rd ".$details_obj->folderRoot." /Q /S \n";
				$details_obj->runingRoot = $details_obj->userNameRoot;
				run_cmd_file($details_obj,$str,$file_name,$next_task);	
			}
			
		}
		return $ans;
	}
?>
<?php
	//Check the session of user
	function check_session($details_obj){
		$arr = array();
		session_start();
		$r = session_id();
		$time = time();
		$str = json_decode(file_get_contents($details_obj->userNameRoot.'\\user_details.json'));
		$arr["user"] = $str;
		if (($str->details->session_id==$r)&&($time-$str->details->session_time < (60*60*30))){
			$arr["flag"] = true;
		}else{
			$arr["flag"] = false;
		}
		return $arr;
	}
	//
	function get_user_list($details_obj){
		$arr= check_session($details_obj);
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
?>
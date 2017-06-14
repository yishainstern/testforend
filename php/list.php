<?php
	function get_user_list($details_obj){
		$ans = array();
		$str = json_decode(file_get_contents($details_obj->userNameRoot.'\\user_details.json'));
		if (!$str->details->userName == $details_obj->userName || !$str->details->password == $details_obj->password){
			$ans['status'] = 1;
			$ans['message'] = "do not try to brake in theaf!!";
		}else {
			$ans['user'] = $str; 
			$ans['status'] = 111;
			$ans['message'] = "user folder created";
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
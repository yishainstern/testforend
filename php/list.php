<?php
	function get_user_list($details_obj){
		$ans = array();
		$str = json_decode(file_get_contents($details_obj->userNameRoot.'user_details.json'));
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

	function get_project_progress($folderRoot){
		$str = json_decode(file_get_contents($folderRoot.'project_details.json'));
		$returnJson['project'] = $str; 
		$returnJson['status'] = 111;
		$returnJson['message'] = "got the progress";	
		return $returnJson;	
	}
?>
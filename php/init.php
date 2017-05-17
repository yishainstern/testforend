<?php
	function json_return($returnJson,$status,$str){
		$returnJson['status'] = $status;
		$returnJson['message'] = $str;
		return $returnJson;		
	}
	function sign_up_new_user($details_obj){
		$ans = array();
		if (is_dir($details_obj->userNameRoot)){
			$ans['status'] = 1;
			$ans['message'] = "there is a folder like this alredy, pick a new name";
		}else {
			mkdir($details_obj->userNameRoot, 0777, true);
			chmod($details_obj->userNameRoot, 0777);
			$obj = new stdClass();
			$obj->details = new stdClass();
			$obj->details->userName = $details_obj->userName;
			$obj->details->password = $details_obj->password;
			$obj->details->first_name = $details_obj->first_name;
			$obj->details->last_name = $details_obj->last_name;
			$obj->details->user_email = $details_obj->user_email;
			$obj->list = array();
			file_put_contents($details_obj->userNameRoot.'\\user_details.json',json_encode($obj));
			$ans['status'] = 111;
			$ans['message'] = "user folder created";
			$ans['user'] = $obj;		
		}
		return $ans;
	}

	function log_in($details_obj){
		$ans = array();
		if (!is_dir($details_obj->userNameRoot)){
			$ans['status'] = 2;
			$ans['message'] = "user does not exsist";			
			return $ans;
		}
		$str = json_decode(file_get_contents($details_obj->userNameRoot.'\\user_details.json'));
		if (!($str->details->userName==$details_obj->userName) || !($str->details->password==$details_obj->password)){
			$ans['status'] = 1;
			$ans['message'] = "do not try to brake in theaf!!";
		}else {
			$ans['status'] = 111;
			$ans['message'] = "welcome";
			$ans['user'] = 	$str;		
		}
		return $ans;
	}
	

?>
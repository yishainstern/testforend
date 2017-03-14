<?php
	function json_return($returnJson,$status,$str){
		$returnJson['status'] = $status;
		$returnJson['message'] = $str;
		return $returnJson;		
	}
	function sign_up_new_user($returnJson,$userNmae,$password,$userNameRoot){
		if (is_dir($userNameRoot)){
			$returnJson['status'] = 1;
			$returnJson['message'] = "there is a folder like this alredy, pick a new name";
		}else {
			mkdir($userNameRoot, 0777, true);
			chmod($userNameRoot, 0777);
			$obj = new stdClass();
			$obj->details = new stdClass();
			$obj->details->userName = $userNmae;
			$obj->details->password = $password;
			file_put_contents($userNameRoot.'user_details.json',json_encode($obj));
			$returnJson['status'] = 111;
			$returnJson['message'] = "user folder created";			
		}
		return $returnJson;
	}

	function log_in($returnJson,$userNmae,$password,$userNameRoot){
		if (!is_dir($userNameRoot)){
			$returnJson['status'] = 2;
			$returnJson['message'] = "user does not exsist";			
			return $returnJson;
		}
		$str = json_decode(file_get_contents($userNameRoot.'user_details.json'));
		if (!($str->details->userName==$userNmae) || !($str->details->password==$password)){
			$returnJson['status'] = 1;
			$returnJson['message'] = "do not try to brake in theaf!!";
		}else {
			$returnJson['status'] = 111;
			$returnJson['message'] = "welcome";			
		}
		return $returnJson;
	}
	

?>
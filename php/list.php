<?php
	function get_user_list($returnJson,$userNmae,$password,$userNameRoot){
		$str = json_decode(file_get_contents($userNameRoot.'user_details.json'));
		if (!$str->details->userName==$userNmae || !$str->details->password==$password){
			$returnJson['status'] = 1;
			$returnJson['message'] = "do not try to brake in theaf!!";
		}else {
			$returnJson['user'] = $str; 
			$returnJson['status'] = 111;
			$returnJson['message'] = "user folder created";
		}
		return $returnJson;
	}
?>
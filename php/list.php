<?php
	function get_user_list($returnJson,$userNmae,$password,$userNameRoot){
		$str = json_decode(file_get_contents($userNameRoot.'user_details.json'));
		if (!$str->details->userName==$userNmae || !$str->details->password==$password){
			$returnJson['status'] = 1;
			$returnJson['message'] = "do not try to brake in theaf!!";
		}else {
			$arr = scandir($userNameRoot);
			$arrSend = array();
			$count = 0;
			for ($i=0; $i < sizeof($arr); $i++) { 
				$tmp_str = ''.$arr[$i];
				if (strlen($tmp_str)>0 && is_dir($userNameRoot.$tmp_str) && is_file($userNameRoot.$tmp_str.'project_details.json')){
					$obj = new stdClass();
					$obj->name = $tmp_str;
					$str_project_details = file_get_contents($userNameRoot.$tmp_str.'project_details.json');
					$p_obj = json_decode($str_project_details);
					$obj->discription = $p_obj->discription;  
					$arrSend[$count] = $obj;
					$count++;
				}
			}
			$returnJson['array'] = $arrSend; 
			$returnJson['status'] = 111;
			$returnJson['message'] = "user folder created";
		}
			
	}
?>
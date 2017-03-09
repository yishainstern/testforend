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
		$str = json_decode(file_get_contents($userNameRoot.'user_details.json'));
		if (!$str->details->userName==$userNmae || !$str->details->password==$password){
			$returnJson['status'] = 1;
			$returnJson['message'] = "do not try to brake in theaf!!";
		}else {
			$returnJson['status'] = 0;
			$returnJson['message'] = "welcome";			
		}
	}
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
			
	function start_and_prepare_folders($returnJson,$folderNmae,$relativeToUserRoot,$localUsers){
		if ((is_dir('users/'.$folderNmae)==TRUE)){
			$returnJson['status'] = 1;
			$returnJson['message'] = "there is a folder like this alredy, pick a new name";
		}else{
			if (is_dir('../users/'.$folderNmae)||is_dir('users/'.$folderNmae))
			{
    			$returnJson['status'] = 2;
				$returnJson['message'] = "Faild to creat the folder for ".$folderNmae;
			}else {
				mkdir($relativeToUserRoot, 0777, true);
				chmod($relativeToUserRoot, 0777);
				mkdir($localUsers, 0777, true);
				chmod($localUsers, 0777);
				mkdir($relativeToUserRoot.'/rootGit', 0777, true);
				chmod($relativeToUserRoot.'/rootGit', 0777);
				mkdir($relativeToUserRoot.'/rootGitOnline', 0777, true);
				chmod($relativeToUserRoot.'/rootGitOnline', 0777);
				mkdir($relativeToUserRoot.'/rootBugs', 0777, true);
				chmod($relativeToUserRoot.'/rootBugs', 0777);
				mkdir($relativeToUserRoot.'/rootLearn', 0777, true);
				chmod($relativeToUserRoot.'/rootLearn', 0777);
				mkdir($relativeToUserRoot.'/out', 0777, true);
				chmod($relativeToUserRoot.'/out', 0777);
				$returnJson['status'] = 0;
				$returnJson['message'] = "create folder, ".$folderNmae." lets clone :)";		
			}
		}
		return $returnJson;
	}
?>
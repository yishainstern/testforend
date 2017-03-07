<?php
	function json_return($returnJson,$status,$str){
		$returnJson['status'] = $status;
		$returnJson['message'] = $str;
		return $returnJson;		
	}
	function sign_up_new_user($returnJson,$userNmae,$password){
		if (is_dir($userNmaeRoot)){
			$returnJson['status'] = 1;
			$returnJson['message'] = "there is a folder like this alredy, pick a new name";			
		}else {
			mkdir($userNmaeRoot, 0777, true);
			chmod($userNmaeRoot, 0777);
		}
		return $returnJson;
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
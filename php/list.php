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
				update_user_hash($arr["user"],$arr["details"]);
				$arr["flag"] = true;
			}else{
				$arr["flag"] = false;
			}
		}
		return $arr;
	}
	//Get the list of all the projects of the user.
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
	//Get all the details for the user of the project.
	function get_project_progress($details_obj){
		$obj = get_all_details_of_project($details_obj);
		if ($obj["problem"] == true	){
			$returnJson['status'] = 555;
			$returnJson['message'] = "Project doe's not exsit.";
		}else {
			$returnJson['project'] = $obj["project"]; 
			$returnJson['status'] = 111;
			$returnJson['message'] = "got the progress";				
		}
		return $returnJson;	
	}
	//Check if conle finished.
	function not_finish($details_obj){
		$arr = get_all_details_of_project($details_obj);
		if (!$arr["project"]->progress->mille_stones->end_clone->flag){
			return true;
		}
		return false;
	}
	//Remove all project files from the server.
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
			}else if(not_finish($details_obj)){
				$ans['status'] = 444;
				$ans['message'] = "Not finish to clone.";
			}else {
				$time = time();
				$arr["details"]->start_remove = true;
				update_user_hash($details_obj,$arr["details"]);
				$list = array();
				for ($i=0; $i < sizeof($arr["user"]->list); $i++) { 
					$tmp = $arr["user"]->list[$i];
					if ($tmp->name != $details_obj->project->folderName){
						array_push($list, $tmp);
					}
				}
				$arr["user"]->list = $list;
				update_user_details($details_obj,$arr["user"]);
				$str = "cd ".$details_obj->user->userNameRoot."\\".$details_obj->project->folderName."\n";
				$str .= "del /Q /S *\n"."cd ../\n";
				$str .= "rd ".$details_obj->project->folderName." /Q /S \n";
				$details_obj->project->runingRoot = $details_obj->user->userNameRoot;
				run_cmd_file($details_obj,$str,"rm","done_remove_project");
				$ans['status'] = 111;
				$ans['user'] = $arr["user"];
			}
			
		}
		return $ans;
	}
	//Update the server that we finished to remove the project.
	function done_remove_project($details_obj){
		$arr = get_all_details_of_user($details_obj);
		$arr["details"]->start_remove = false;
		update_user_hash($details_obj,$arr["details"]);
	}
?>
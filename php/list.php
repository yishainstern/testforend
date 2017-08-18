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
		$arr = check_session($details_obj);
		$ans = array();
		if ($arr["flag"] == false){
			$ans['status'] = 555;
			$ans['message'] = "Session expired or not exists.";
		}else {
			$obj = get_all_details_of_project($details_obj);
			if ($obj["problem"] == true	){
				$ans['status'] = 555;
				$ans['message'] = "Project doe's not exsit.";
			}else {
				$ans['project'] = $obj["project"]; 
				$ans['user'] = $arr["user"]; 
				$ans['status'] = 111;
				$ans['message'] = "got the progress";				
			}
		}
		return $ans;	
	}
	//Get all the details for the user of the project.
	function get_all_need($details_obj){
		$arr = check_session($details_obj);
		$ans = array();
		if ($arr["flag"] == false){
			$ans['status'] = 555;
			$ans['message'] = "Session expired or not exists.";
		}else {
			$obj = get_all_details_of_project($details_obj);
			if ($obj["problem"] == true	){
				$ans['status'] = 555;
				$ans['message'] = "Project doe's not exsit.";
			}else {
				$ans['project'] = $obj["project"]; 
				$ans['user'] = $arr["user"]; 
				$ans['details'] = $arr["details"]; 
				$ans['status'] = 111;
				$ans['message'] = "got the progress";				
			}
		}
		return $ans;	
	}
	//Check if conle finished.
	function not_finish($details_obj){
		$arr = get_all_details_of_project($details_obj);
		if (!$arr["project"]->progress->mille_stones->end_clone->flag){
			return true;
		}
		if ($arr["project"]->progress->mille_stones->start_offline->flag){
			if(!$arr["project"]->progress->mille_stones->end_offline->flag){
				if(!$arr["project"]->problem){
					return true;
				}	
			}
			if(!$arr["project"]->progress->mille_stones->get_prediction->flag){
				if(!$arr["project"]->problem){
					return true;
				}	
			}
		}
		return false;
	}
	//Remove all project files from the server.
	function remove_project($details_obj){
		$ans = array();
		$tmp_arr = get_all_need($details_obj);
		if ($tmp_arr['status']==111){
			if ($tmp_arr["details"]->start_remove == true){
				$ans['status'] = 444;
				$ans['message'] = "Still removing old project.";
			}else if(not_finish($details_obj)){
				$ans['status'] = 444;
				$ans['message'] = "Not finish to clone or learning task";
			}else {
				$time = time();
				$time_str = "" + $time;
				$tmp_arr["details"]->start_remove = true;
				update_user_hash($tmp_arr["user"],$tmp_arr["details"]);
				$list = array();
				for ($i=0; $i < sizeof($tmp_arr["user"]->list); $i++) { 
					$tmp = $tmp_arr["user"]->list[$i];
					if ($tmp->name != $details_obj->project->folderName){
						array_push($list, $tmp);
					}
				}
				$tmp_arr["user"]->list = $list;
				$new_name = $time_str.$tmp_arr["user"]->userName;
				$old_name = $tmp_arr["project"]->folderName;
				update_user_details($tmp_arr["user"]);
				$str = "cd ".$tmp_arr["user"]->userNameRoot."\\".$old_name."\n";
				$str .= "del /Q /S *\n";
				$str .= "cd ../\n";
				$str .= "rename ".$old_name." ".$new_name."\n"; 
				$str .= "rd ".$new_name." /Q /S \n";
				$tmp_arr["project"]->runingRoot = $tmp_arr["user"]->userNameRoot;
				//($details_obj,$project,$user,$current_string,$file_name,$next_task)
				run_cmd_file($details_obj,$tmp_arr["project"],$tmp_arr["user"],$str,"rm","done_remove_project");
				$ans['status'] = 111;
				$ans['user'] = $tmp_arr["user"];
			}
		}else {
			$ans['status'] = 555;
			$ans['message'] = "Session expired or not exists.";
		}
		return $ans;
	}
	//Update the server that we finished to remove the project.
	function done_remove_project($details_obj){
		$arr = get_all_details_of_user($details_obj);
		$arr["details"]->start_remove = false;
		update_user_hash($arr["user"],$arr["details"]);
	}
?>
<?php
	//Hash function for password
	function get_hash($string){
		return hash('md5', $string);
	}
	//Sgin up a new user and create him a session
	function sign_up_new_user($details_obj){
		$user = $details_obj->user;
		$root_for_user = $details_obj->root.$details_obj->user->userName;
		session_start();
		$r = session_id();
		$time = time();
		$ans = array();
		if (is_dir($root_for_user)){
			$ans['status'] = 1;
			$ans['message'] = "There is a folder like this already, pick a new name.";
		}else {
			mkdir($root_for_user, 0777, true);
			$obj = new stdClass();
			$hash = new stdClass();
			$obj->userName = $user->userName;
			$obj->first_name = $user->first_name;
			$obj->last_name = $user->last_name;
			$obj->user_email = $user->user_email;
			$obj->userNameRoot = $details_obj->root.$obj->userName;
			$obj->user_details = $obj->userNameRoot.'\\user_details.json';
			$obj->user_server_details = $obj->userNameRoot.'\\user_server_details.json';
			$hash->session_id = $r;
			$hash->session_time = "".$time;
			$hash->start_remove = false;
			$obj->list = array();
			$hash->password = get_hash($user->password);
			update_user_details($obj);
			update_user_hash($obj,$hash);
			$ans['status'] = 111;
			$ans['message'] = "User folder created";
			$ans['user'] = $obj;		
		}
		return $ans;
	}
	//log in user
	function log_in($details_obj){
		$user = $details_obj->user;
		$ans = array();
		
		session_start();
		$r = session_id();
		$time = time();
		$arr = get_all_details_of_user($details_obj);
		if ($arr["problem"]==true){
			$ans['status'] = 2;
			$ans['message'] = "User does not exist.";			
			return $ans;
		}
		if (!($arr["user"]->userName==$user->userName) || !($arr["details"]->password==get_hash($user->password))){
			$ans['status'] = 1;
			$ans['message'] = "Do not try to break in, thief!!";
			session_unset(); // remove all session variables
			session_destroy(); // destroy the session 
		}else {
			session_regenerate_id();
			$r = session_id();
			$arr["details"]->session_id = $r;
			$arr["details"]->session_time = "".$time;
			update_user_hash($arr["user"],$arr["details"]);
			$ans['status'] = 111;
			$ans['message'] = "Welcome";
			$ans['user'] = 	$arr["user"];		
		}
		return $ans;
	}
?>
<?php
	//Hash function for password
	function get_hash($string){
		return hash('md5', $string);
	}
	//Sgin up a new user and create him a session
	function sign_up_new_user($details_obj){
		$user = $details_obj->user;
		session_start();
		$r = session_id();
		$time = time();
		$ans = array();
		if (is_dir($user->userNameRoot)){
			$ans['status'] = 1;
			$ans['message'] = "There is a folder like this already, pick a new name.";
		}else {
			mkdir($user->userNameRoot, 0777, true);
			chmod($user->userNameRoot, 0777);
			$obj = new stdClass();
			$hash = new stdClass();
			$obj->userName = $user->userName;
			$obj->first_name = $user->first_name;
			$obj->last_name = $user->last_name;
			$obj->user_email = $user->user_email;
			$hash->session_id = $r;
			$hash->session_time = "".$time;
			$hash->start_remove = false;
			$obj->list = array();
			$hash->password = get_hash($user->password);
			update_user_details($details_obj,$obj);
			update_user_hash($details_obj,$hash);
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
		if (!is_dir($user->userNameRoot)){
			$ans['status'] = 2;
			$ans['message'] = "User does not exist.";			
			return $ans;
		}
		session_start();
		$r = session_id();
		$time = time();
		$arr = get_all_details_of_user($details_obj);
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
			update_user_hash($details_obj,$arr["details"]);
			$ans['status'] = 111;
			$ans['message'] = "Welcome";
			$ans['user'] = 	$arr["user"];		
		}
		return $ans;
	}
?>
<?php
	//Hash function for password
	function get_hash($string){
		return hash('md5', $string);
	}
	//Sgin up a new user and create him a session
	function sign_up_new_user($details_obj){
		session_start();
		$r = session_id();
		$time = time();
		$ans = array();
		if (is_dir($details_obj->userNameRoot)){
			$ans['status'] = 1;
			$ans['message'] = "There is a folder like this already, pick a new name.";
		}else {
			mkdir($details_obj->userNameRoot, 0777, true);
			chmod($details_obj->userNameRoot, 0777);
			$obj = new stdClass();
			$obj->details = new stdClass();
			$obj->details->userName = $details_obj->userName;
			$obj->details->first_name = $details_obj->first_name;
			$obj->details->last_name = $details_obj->last_name;
			$obj->details->user_email = $details_obj->user_email;
			$obj->details->session_id = $r;
			$obj->details->session_time = $time;
			$obj->list = array();
			$tmp_password = get_hash($details_obj->password);
			file_put_contents($details_obj->userNameRoot.'\\user_details.json',json_encode($obj));
			file_put_contents($details_obj->userNameRoot.'\\hash.txt',$tmp_password);
			$ans['status'] = 111;
			$ans['message'] = "User folder created";
			$ans['user'] = $obj;		
		}
		return $ans;
	}
	//log in user
	function log_in($details_obj){
		$ans = array();
		if (!is_dir($details_obj->userNameRoot)){
			$ans['status'] = 2;
			$ans['message'] = "User does not exist.";			
			return $ans;
		}
		session_start();
		$r = session_id();
		$time = time();
		$str = json_decode(file_get_contents($details_obj->userNameRoot.'\\user_details.json'));
		$pas = file_get_contents($details_obj->userNameRoot.'\\hash.txt');
		if (!($str->details->userName==$details_obj->userName) || !($pas==get_hash($details_obj->password))){
			$ans['status'] = 1;
			$ans['message'] = "Do not try to break in, thief!!";
			session_unset(); // remove all session variables
			session_destroy(); // destroy the session 
		}else {
			session_regenerate_id();
			$r = session_id();
			$str->details->session_id = $r;
			$str->details->session_time = $time;
			file_put_contents($details_obj->userNameRoot.'\\user_details.json',json_encode($str));
			$ans['status'] = 111;
			$ans['message'] = "Welcome";
			$ans['user'] = 	$str;		
		}
		return $ans;
	}
?>
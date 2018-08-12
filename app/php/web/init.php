<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	require __DIR__.'/../vendor/autoload.php';
	require __DIR__.'/../web/PHPMailer/src/Exception.php';
	require __DIR__.'/../web/PHPMailer/src/PHPMailer.php';
	require __DIR__.'/../web/PHPMailer/src/SMTP.php';
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
	//recover user account
	function recover_account($details_obj){
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
		////////
		//generate new password
		$user = $arr['user'];
		$user_details = $arr['details'];
		$obj = new stdClass();
		$hash = new stdClass();
		$obj->userName = $user->userName;
		$obj->first_name = $user->first_name;
		$obj->last_name = $user->last_name;
		$obj->user_email = $user->user_email;
		$obj->userNameRoot = $details_obj->root.$obj->userName;
		$obj->user_details = $obj->userNameRoot.'\\user_details.json';
		$obj->user_server_details = $obj->userNameRoot.'\\user_server_details.json';
		$generator ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789";
		$generated_length = rand(6,8);
		$generated_password = substr(str_shuffle($generator), 0, $generated_length);
		$hash = new stdClass();
		$hash->session_id = $r;
		$hash->session_time = "".$time;
		$hash->start_remove = false;
		$hash->password = get_hash($generated_password);
		update_user_details($obj);
		update_user_hash($obj,$hash);
		////////
		$email_account_arr = get_all_details_of_email_account($details_obj);
		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;
		//Set the hostname of the mail server
		$mail->Host = 'smtp.gmail.com';
		// use
		// $mail->Host = gethostbyname('smtp.gmail.com');
		// if your network does not support SMTP over IPv6
		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$mail->Port = 587;
		//Set the encryption system to use - ssl (deprecated) or tls
		$mail->SMTPSecure = 'tls';
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = $email_account_arr['username'];
		//Password to use for SMTP authentication
		$mail->Password = $email_account_arr['password'];
		//Set who the message is to be sent from
		$mail->setFrom($email_account_arr['username'], 'deBGUer');
		//Set an alternative reply-to address
		$mail->addReplyTo('replyto@example.com', 'First Last');
		//Set who the message is to be sent to
		$mail->addAddress($arr['user']->user_email);
		//Set the subject line
		$mail->Subject = 'DeBGUer Account recovery';
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		//$mail->msgHTML(file_get_contents('hello.html'), __DIR__);
		$mail->Body = "Your new password is:<br><br>".$generated_password;
		//Replace the plain text body with one created manually
		$mail->AltBody = 'world';
		//send the message, check for errors
		if (!$mail->send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			$r = session_id();
			$hash->session_id = $r;
			$hash->session_time = "".$time;
			$hash->start_remove = false;
			update_user_hash($arr["user"],$hash);
			$ans['status'] = 111;
			$ans['message'] = "Welcome";
			$ans['user'] = 	$arr["user"];
			return $ans;
			//Section 2: IMAP
			//Uncomment these to save your message in the 'Sent Mail' folder.
			#if (save_mail($mail)) {
			#    echo "Message saved!";
			#}
		}
	}
?>
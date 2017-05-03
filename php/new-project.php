<?php	
	function update_user_details($userNameRoot,$folderName,$discription){
		//echo ($userNameRoot.'user_details.json '."\n");
		$str = json_decode(file_get_contents($userNameRoot.'user_details.json'));
		//var_dump($str);
		//echo (is_array($str->list));
		$arr = $str->list;
		$count = sizeof($arr);
		$obj = new stdClass();
		$obj->name = $folderName;
		$obj->discription = $discription;
		$arr[$count] = $obj; 
		$str->list = $arr;
		file_put_contents($userNameRoot.'user_details.json',json_encode($str));
		return $str;
	}
	function get_progress_array(){
		$ans = array();
		array_push($ans, (object) array('flag'=>TRUE,'name'=>'create_folders','text'=>'create folders in our server file system were we will store your project', 'title'=>'create folders'));//1
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'start_clone','text'=>'clone your project from git to our server file system', 'title'=>'start_clone'));//2
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'end_clone','text'=>'checking if the clone task is done', 'title'=>'end clone'));//3				
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'upload_bug_file','text'=>'upload a csv file from bugzila of known bugs and tell us the versions you want us to check', 'title'=>'upload bug file'));//4
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'start_offline','text'=>'use your code as a data-base to do a offline learning of the code', 'title'=>'start offline'));//5
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'end_offline','text'=>'code studing in offline is done', 'title'=>'end offline'));//6
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'pick_version','text'=>'pick a version for runing some testes on your code', 'title'=>'pick version'));//7
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'check_version','text'=>'check if the version that was picked is the version that you wanted to be tested', 'title'=>'check version'));//8
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'prepare_jar','text'=>'notify us to prepare a jar to run some tests on your code', 'title'=>'prepare jar'));//9
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'update_pom','text'=>'update your pom.xml file for running are task', 'title'=>'update pom.xml file'));//9		
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'prepare_mvn','text'=>'notify us to get ready all files to run mavn on your ocmputer', 'title'=>'prepare mvn'));//10
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'start_testing','text'=>'run maven in the server to test you code and get failers of the code with maven and surfire', 'title'=>'start testing'));//11
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'end_testing','text'=>'maven task was done and we have some file with discription of failers', 'title'=>'end testing'));//12
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'prepare_prediction','text'=>'use all outputs from previous tasks to get pridction', 'title'=>'prepare prediction'));//13
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'run_prediction','text'=>'run prediction task', 'title'=>'run prediction'));//13
		array_push($ans, (object) array('flag'=>FALSE,'name'=>'get_prediction','text'=>'get a prediction of your code', 'title'=>'get prediction'));//13
		return $ans;
	}
	function update_project_details($folderName,$discription,$folderRoot){
		$obj = new stdClass();
		$obj->details = new stdClass();
		$obj->details->name = $folderName;
		$obj->details->discription = $discription;
		$obj->details->progress = get_progress_array();
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
		return $obj;
	}
	function start_and_prepare_folders($folderRoot,$userProjectRoot,$DebuugerRoot,$outputPython,$runingRoot,$userNameRoot,$folderName,$discription,$bugRoot){
		if ((is_dir($folderRoot)==TRUE)){
			$returnJson['status'] = 1;
			$returnJson['message'] = "you have already a project with this name, pick a new name";
		}else{
			mkdir($folderRoot, 0777, true);
			mkdir($userProjectRoot, 0777, true);
			mkdir($DebuugerRoot, 0777, true);
			mkdir($outputPython, 0777, true);
			mkdir($runingRoot, 0777, true);
			mkdir($bugRoot, 0777, true);
			$user_details = update_user_details($userNameRoot,$folderName,$discription);
			$project_details = update_project_details($folderName,$discription,$folderRoot);
			$returnJson['status'] = 111;
			$returnJson['message'] = "created folders, lets clone :)";		
			$returnJson['user'] = $user_details;
			$returnJson['project'] = $project_details;
		}
		return $returnJson;
	}
	//
	//clone from github the latest versin of Debugger and the project of user
	function clone_from_git_to_server($returnJson, $DebuugerRoot, $gitUrl, $startGit, $userProjectRoot, $gitName, $relativeToUserRoot, $amirGit, $runingRoot, $folderRoot){
		pclose(popen($startGit." ".$gitUrl." ".$userProjectRoot.$gitName." 2>".$runingRoot."\\proj.log", "w"));
		pclose(popen($startGit." ".$amirGit." ".$DebuugerRoot."Debugger 2>".$runingRoot."\\Debugger.log", "w"));
		file_put_contents($runingRoot."\\goD.sh", "#!/bin/bash\n tail -n 1 Debugger.log");
		file_put_contents($runingRoot."\\goG.sh", "#!/bin/bash\n tail -n 1 proj.log");
		$obj = json_decode(file_get_contents($folderRoot.'project_details.json'));
		$obj->details->gitName = $gitName;
		$obj->details->gitUrl = $gitUrl;
		$obj->details->progress[1]->flag = TRUE;
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
		$returnJson['status'] = 111;
		$returnJson['message'] = "wait 5 minites and check clone";
		$returnJson['project'] = $obj; 
		return $returnJson;
	}
	//
	//check if clone task is done.
	function check_if_clone_is_done($returnJson,$runingRoot,$folderRoot){
		$old_path = getcwd();
		chdir($runingRoot);
		$data = file('proj.log');
		$output = $data[count($data)-1];
		$data = file('Debugger.log');
		$output1 = $data[count($data)-1];
		$flag1 = strpos($output, "done");
		$flag21 =strpos($output, "Checking out files: 100%");
		$flag22 =strpos($output, "Resolving deltas: 100%");
		$flag3 = strpos($output1, "done");
		$flag41 =strpos($output, "Checking out files: 100%");
		$flag42 =strpos($output, "Resolving deltas: 100%");
		$flag5 = strpos($output, "Fatal");
		$flag6 = strpos($output1, "Fatal");
		$obj = json_decode(file_get_contents($folderRoot.'project_details.json'));
		if ($flag1 && ($flag21 || $flag22) && $flag3 && ($flag41 || $flag42)){
			$obj->details->progress[2]->flag = TRUE;
			$returnJson['status'] = 111;
			$returnJson['message'] = "all cloned";
			$returnJson['project'] = $obj; 
		}else if ($flag5 || $flag6){
			$obj->details->try_agin = TRUE;
			$returnJson['status'] = 2;
			$returnJson['message'] = 'some failer in server try agin....';
			$returnJson['project'] = $obj;
		}else {
			$returnJson['status'] = 1;
			$returnJson['message'] = "did not finish cloning, check agin in 5 minites";
			$returnJson['project'] = $obj;
		}
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
		return $returnJson;		
	}
	function try_agin($returnJson,$DebuugerRoot,$gitUrl,$startGit,$userProjectRoot,$gitName,$relativeToUserRoot,$amirGit,$runingRoot,$folderRoot){
		$returnJson = clone_from_git_to_server($returnJson,$DebuugerRoot,$gitUrl,$startGit,$userProjectRoot,$gitName,$relativeToUserRoot,$amirGit,$runingRoot,$folderRoot);
	}
?>
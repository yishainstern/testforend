<?php 

	//clone from github the latest versin of Debugger and the project of user
	function clone_from_git_to_server($returnJson,$DebuugerRoot,$gitUrl,$startGit,$userProjectRoot,$gitName,$relativeToUserRoot,$amirGit){
		pclose(popen($startGit." ".$gitUrl." ".$userProjectRoot.$gitName." 2>".$relativeToUserRoot."\\proj.log", "w"));
		pclose(popen($startGit." ".$amirGit." ".$DebuugerRoot."Debugger 2>".$relativeToUserRoot."\\Debugger.log", "w"));
		file_put_contents($relativeToUserRoot."/goD.sh", "#!/bin/bash\n tail -n 1 Debugger.log");
		file_put_contents($relativeToUserRoot."/goG.sh", "#!/bin/bash\n tail -n 1 proj.log");
		return json_return($returnJson,0,"wait 5 minites and check clone");
	}

	//is clone done
	function check_if_clone_is_done($returnJson,$relativeToUserRoot){
		$old_path = getcwd();
		chdir($relativeToUserRoot);
		$output = shell_exec('bash goD.sh');
		$output1 = shell_exec('bash goG.sh');
		$flag1 = strpos($output, "done");
		$flag2 =strpos($output, "Checking out files: 100%");
		$flag3 = strpos($output1, "done");
		$flag4 = strpos($output1, "Checking out files: 100%");
		if ($flag1 && $flag2 && $flag3 && $flag4){
			$returnJson = ($returnJson,0,"all cloned");		
		}else {
			$returnJson = ($returnJson,1,"did not finish");
		}
		return $returnJson;		
	}

	//bug file task
	function put_bug_file_in_place($fileObj,$relativeToUserRoot){
		$tmp_name = $fileObj["tmp_name"];
        $name = basename($fileObj["name"]);
        $uploadDir = $relativeToUserRoot."/rootBugs";
        move_uploaded_file($tmp_name, $uploadDir."/".$name);
        chmod($relativeToUserRoot."/rootBugs/".$name, 0777);
        return $name;
	}

	//antConf.txt create
	function creat_conf_for_offline($outputPython,$userProjectRoot,$gitName,$name,$all_versions,$folderRoot){
        $str = 'workingDir='.$outputPython."\r\n";
        $str = $str.'git='.$userProjectRoot.$gitName."\r\n";
        $str = $str.'bugs='.$folderRoot."\\rootBugs\\".$name."\r\n";
        $str = $str."vers=(". $all_versions.")";
        file_put_contents($DebuugerRoot."Debugger\\learner\\antConf.txt",$str); 
	}

	function prepare_runing_file_for_offline_task($folderNmae){
        $str = '<?php pclose(popen("start /B python '.'../../users/'.$folderNmae.'/rootLearn/Debugger/learner/wrapper.py ../../users/'.$folderNmae.'/rootLearn/Debugger/learner/antConf.txt learn", "w")); ?>';
        file_put_contents("users/".$folderNmae."/index.php",$str);		
	}
	//add bugs file and get ready to run
	function add_bug_file_and_prepare_to_run($returnJson,$relativeToUserRoot,$fileObj,$outputPython,$all_versions,$folderRoot,$gitName){
		if ($fileObj&&$fileObj["error"]==UPLOAD_ERR_OK&& $fileObj["tmp_name"]){
			$name = put_bug_file_in_place($fileObj,$relativeToUserRoot);
			creat_conf_for_offline($outputPython,$userProjectRoot,$gitName,$name,$all_versions,$folderRoot);
			prepare_runing_file_for_offline_task($folderNmae);
        	$returnJson = ($returnJson,0,"all ready for offline task");
		}else {
			$returnJson = ($returnJson,1,"somthing went rong...try again");
		}
		return $returnJson;
	}

	function run_python_code($domain,$folderNmae){
		$opts = array(
  			'http'=>array(
    			'method'=>"GET",
    			'header'=>"Accept-language: en\r\n" .
              	"Cookie: foo=bar\r\n"
  			)
		);
		$context = stream_context_create($opts);
		// Open the file using the HTTP headers set above
		$file = file_get_contents($domain."/users/".$folderNmae."/index.php", false, $context);
		return json_return($returnJson,0,"starting offline task....check agin in 7 hours if done");
	}



?>
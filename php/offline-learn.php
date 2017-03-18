<?php 
	//
	//put the scv file in the right place.
	function put_bug_file_in_place($fileObj,$folderRoot,$bugRoot){
		$tmp_name = $fileObj["tmp_name"];
        $name = basename($fileObj["name"]);
        $uploadDir = $folderRoot."rootBugs";
        move_uploaded_file($tmp_name, $bugRoot."\\".$name);
        chmod($uploadDir."\\".$name, 0777);
        return $name;
/*		foreach ($_FILES["csvFile"]["error"] as $key => $error) {
		    if ($error == UPLOAD_ERR_OK) {
		        $tmp_name = $_FILES["pictures"]["tmp_name"][$key];
		        // basename() may prevent filesystem traversal attacks;
		        // further validation/sanitation of the filename may be appropriate
		        $name = basename($_FILES["csvFile"]["name"][$key]);
		        move_uploaded_file($tmp_name, $folderRoot.$name);
		    }
		}
*/
	}
	//
	//create antConf.txt that contains all the paths that are needed for the Python project.
	function creat_conf_for_offline($outputPython,$userProjectRoot,$gitName,$name,$all_versions,$folderRoot,$DebuugerRoot){
        $str = 'workingDir='.$outputPython."\r\n";
        $str = $str.'git='.$userProjectRoot.$gitName."\r\n";
        $str = $str.'bugs='.$folderRoot."\\rootBugs\\".$name."\r\n";
        $str = $str."vers=(". $all_versions.")";
        file_put_contents($DebuugerRoot."Debugger\\learner\\antConf.txt",$str); 
	}
	//
	//preparw a file that will run the Python project.
	function prepare_runing_file_for_offline_task($folderNmae){
        /*$str = '<?php pclose(popen("start /B python '.'../../users/'.$folderNmae.'/rootLearn/Debugger/learner/wrapper.py ../../users/'.$folderNmae.'/rootLearn/Debugger/learner/antConf.txt learn", "w")); ?>';
        file_put_contents("users/".$folderNmae."/index.php",$str);*/		
	}

	function update_details($folderRoot,$fileObj,$all_versions){
		$obj = json_decode(file_get_contents($folderRoot.'project_details.json'));
		$obj->details->progress[3]->flag = TRUE;
		if ($fileObj["name"]){
			$obj->details->bugFileName = $fileObj["name"];
		}
		$obj->details->versions = $all_versions;
		file_put_contents($folderRoot.'project_details.json',json_encode($obj));
		return $obj;
	}
	//
	//get a scv file from user than contains a bug list in a spesific format.
	function add_bug_file_and_prepare_to_run($returnJson,$relativeToUserRoot,$fileObj,$outputPython,$all_versions,$folderRoot,$gitName,$userProjectRoot,$DebuugerRoot,$folderNmae,$runingRoot,$bugRoot){
		if ($fileObj&&$fileObj["error"]==UPLOAD_ERR_OK&& $fileObj["tmp_name"]){
			$name = put_bug_file_in_place($fileObj,$folderRoot,$bugRoot);
			creat_conf_for_offline($outputPython,$userProjectRoot,$gitName,$name,$all_versions,$folderRoot,$DebuugerRoot);
			prepare_runing_file_for_offline_task($folderNmae);
			 $project_details = update_details($folderRoot,$fileObj,$all_versions);
			$returnJson['status'] = 111;
			$returnJson['message'] = "all ready for offline task:)";
			$returnJson['project'] = $project_details;
		}else {
			$returnJson = json_return($returnJson,1,"somthing went rong...try again");
		}
		return $returnJson;
	}
	//
	//use http get request to run the python project.
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
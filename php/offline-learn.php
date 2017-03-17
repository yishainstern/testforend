<?php 
	//
	//put the scv file in the right place.
	function put_bug_file_in_place($fileObj,$relativeToUserRoot){
		$tmp_name = $fileObj["tmp_name"];
        $name = basename($fileObj["name"]);
        $uploadDir = $relativeToUserRoot."/rootBugs";
        move_uploaded_file($tmp_name, $uploadDir."/".$name);
        chmod($relativeToUserRoot."/rootBugs/".$name, 0777);
        return $name;
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
        $str = '<?php pclose(popen("start /B python '.'../../users/'.$folderNmae.'/rootLearn/Debugger/learner/wrapper.py ../../users/'.$folderNmae.'/rootLearn/Debugger/learner/antConf.txt learn", "w")); ?>';
        file_put_contents("users/".$folderNmae."/index.php",$str);		
	}
	//
	//get a scv file from user than contains a bug list in a spesific format.
	function add_bug_file_and_prepare_to_run($returnJson,$relativeToUserRoot,$fileObj,$outputPython,$all_versions,$folderRoot,$gitName,$userProjectRoot,$DebuugerRoot,$folderNmae,$runingRoot){
		if ($fileObj&&$fileObj["error"]==UPLOAD_ERR_OK&& $fileObj["tmp_name"]){
			$name = put_bug_file_in_place($fileObj,$relativeToUserRoot);
			creat_conf_for_offline($outputPython,$userProjectRoot,$gitName,$name,$all_versions,$folderRoot,$DebuugerRoot);
			prepare_runing_file_for_offline_task($folderNmae);
        	$returnJson = json_return($returnJson,0,"all ready for offline task");
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
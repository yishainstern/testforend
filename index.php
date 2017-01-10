
<?php
	require_once 'myConf.php';
	header('Content-Type: application/json');
	error_reporting(E_ALL);
	$returnJson = array();
	require_once("javapart.php"); 	
	if ($task=='open folder'){
		if ((is_dir('users/'.$folderNmae)==TRUE)){
			$returnJson['status'] = 1;
			$returnJson['message'] = "there is a folder like this alredy, pick a new name";
			echo json_encode((object)$returnJson);
		}else{
			if (is_dir('../users/'.$folderNmae)||is_dir('users/'.$folderNmae))
			{
    			$returnJson['status'] = 2;
				$returnJson['message'] = "Faild to creat the folder for ".$folderNmae;
			}else {
				mkdir($relativeToUserRoot, 0777, true);
				chmod($relativeToUserRoot, 0777);
				mkdir($localUsers, 0777, true);
				chmod($localUsers, 0777);
				mkdir($relativeToUserRoot.'/rootGit', 0777, true);
				chmod($relativeToUserRoot.'/rootGit', 0777);
				mkdir($relativeToUserRoot.'/rootBugs', 0777, true);
				chmod($relativeToUserRoot.'/rootBugs', 0777);
				mkdir($relativeToUserRoot.'/rootLearn', 0777, true);
				chmod($relativeToUserRoot.'/rootLearn', 0777);
				mkdir($relativeToUserRoot.'/out', 0777, true);
				chmod($relativeToUserRoot.'/out', 0777);
				$returnJson['status'] = 0;
				$returnJson['message'] = "create folder, ".$folderNmae." lets clone :)";		
			}
			echo json_encode((object)$returnJson);
		}
	}elseif ($task=='clone git') {
		pclose(popen($startGit." ".$_POST["git"]." ".$userProjectRoot.$gitName." 2>".$relativeToUserRoot."\\proj.log", "w"));
		pclose(popen($startGit." ".$amirGit." ".$DebuugerRoot."Debugger 2>".$relativeToUserRoot."\\Debugger.log", "w"));
		file_put_contents($relativeToUserRoot."/goD.sh", "#!/bin/bash\n tail -n 1 Debugger.log");
		file_put_contents($relativeToUserRoot."/goG.sh", "#!/bin/bash\n tail -n 1 proj.log");
		$returnJson['status'] = 0;
		$returnJson['message'] = "wait 5 minites and check clone";
		echo json_encode((object)$returnJson);
	}elseif ($task=="check git"){
		$old_path = getcwd();
		chdir($relativeToUserRoot);
		//echo( getcwd());
		$output = shell_exec('bash goD.sh');
		$output1 = shell_exec('bash goG.sh');
		$flag1 = strpos($output, "done");
		$flag2 =strpos($output, "Checking out files: 100%");
		$flag3 = strpos($output1, "done");
		$flag4 = strpos($output1, "Checking out files: 100%");
		if ($flag1 && $flag2 && $flag3 && $flag4){
			$returnJson['status'] = 0;
			$returnJson['message'] = "all cloned";		
		}else {
			$returnJson['status'] = 1;
			$returnJson['message'] = "did not finish";
		}
		echo json_encode((object)$returnJson);
	}elseif ($task=="add version") {
		//var_dump($_FILES);
		if($_FILES["csvFile"]["error"]==UPLOAD_ERR_OK){
			$tmp_name = $_FILES["csvFile"]["tmp_name"];
        	// basename() may prevent filesystem traversal attacks;
        	// further validation/sanitation of the filename may be appropriate
        	$name = basename($_FILES["csvFile"]["name"]);
        	$uploadDir = $relativeToUserRoot."/rootBugs";
        	move_uploaded_file($tmp_name, $uploadDir."/".$name);
        	chmod($relativeToUserRoot."/rootBugs/".$name, 0777);
        	$str = 'workingDir='.$outputPython.'\\r\\n';
        	$str = $str.'git='.$userProjectRoot.$gitName."\r\n";
        	$str = $str.'bugs='.$folderRoot."\\rootBugs\\".$name."\r\n";
        	$str = $str."vers=(". $_POST["ver"].")";
        	file_put_contents($DebuugerRoot."Debugger\\learner\\antConf.txt",$str); 
        	$str = '<?php pclose(popen("start /B python '.'../../users/'.$folderNmae.'/rootLearn/Debugger/learner/wrapper.py ../../users/'.$folderNmae.'/rootLearn/Debugger/learner/antConf.txt learn", "w")); ?>';
        	file_put_contents("users/".$folderNmae."/index.php",$str); 
		}
	}elseif ($task=="run Python") {
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
		//echo($file);
	}elseif ($task=="run Pom") {
		$str = $userProjectRoot.$gitName."\\".$pomPath;
		exec("dir /s /b " .$str."\*pom.xml* > ".$relativeToUserRoot."\\log.txt");
		$arr = explode("\n",file_get_contents($relativeToUserRoot."\\log.txt"));
		for ($i=0; $i < sizeof($arr) ; $i++) { 
			set_time_limit(20);
			$pathForJar = $folderRoot.$jarName;
			$pathForPathtx = $folderRoot."path.txt";
			pastPom($arr[$i],$pathForJar,$pathForPathtx);
		}
	}elseif ($task=="clean mvn"){
		set_time_limit(100);
		$relativeToPoomRoot = $relativeToUserRoot."\\rootLearn\\Debugger\\my-app";
		exec("mvn -f ".$relativeToPoomRoot." clean install");
		$returnJson['status'] = 0;
		$returnJson['message'] = "maven-ready";	
		echo json_encode((object)$returnJson);	
	}elseif ($task=="mvn install"){
		set_time_limit(100);
		exec("mvn -f ..\\users\\".$folderNmae."\\rootLearn\\Debugger\\my-app install");
	}elseif($task=="pathTxt"){
		$str = $mavenroot."\r\n".$userProjectRoot.$gitName;
		file_put_contents($folderRoot."path.txt",$str);
		$oldTarget = $DebuugerRoot.'Debugger\\my-app\\target\\'.$jarName;
		$newTarget = $folderRoot.$jarName;
		copy($oldTarget, $newTarget);
	}elseif($task=="run java"){
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName."\\".$pomPath);
		$command = "start /B mvn clean install --fail-never >getLog.txt";
		echo($command);
		pclose(popen($command, "w"));		
	}elseif ($task=="chenge version"){
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName);
		$command = "start /B git checkout ".$newVersion." 2>newVersion.txt";
		pclose(popen($command, "w")); 
	}elseif ($task=="check version") {
		$old_path = getcwd();
		chdir($userProjectRoot.$gitName);
		$command = "git branch 2>branch.txt";
		$checher = exec($command);
		echo($checher);

	}
	
	//echo json_encode((object)$returnJson);
?>
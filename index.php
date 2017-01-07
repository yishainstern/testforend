
<?php
	header('Content-Type: application/json');
	error_reporting(E_ALL);
	$returnJson = array();
	require_once("javapart.php");
	$gitName = $_POST["gitName"];
	$folderNmae = $_POST["id"];
	$root = 'C:\\Users\\pc-home\\Desktop\\Github\\users\\';
	$folderRoot = $root.$folderNmae.'\\';
	$userProjectRoot = $folderRoot.'rootGit\\';
	$DebuugerRoot = $folderRoot.'rootLearn\\';
	$outputPython = $folderRoot.'out';
	$relativeToUserRoot = '..\\users\\'.$folderNmae;
	$localUsers = 'users\\'.$folderNmae;
	$mavenroot = "C:\\Users\\pc-home\\.m2\\repository";
	$pomPath = $_POST["pomPath"];
	$amirGit = "https://github.com/amir9979/Debugger.git";
	if ($_POST["task"]=='open folder'){
		if ((is_dir('users/'.$folderNmae)==TRUE)){
			$returnJson['status'] = 1;
			$returnJson['message'] = "there is a folder like this alredy, pick a new name";
			echo json_encode((object)$returnJson);
		}else{
			if (is_dir('../users/'.$folderNmae)||is_dir('users/'.$folderNmae))
			{
    			$returnJson['status'] = 2;
				$returnJson['message'] = "Faild to creat the folder for".$folderNmae;
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
				$returnJson['message'] = "create folder,".$folderNmae." lets clone :)";		
			}
			echo json_encode((object)$returnJson);
		}
	}elseif ($_POST["task"]=='clone git') {
		$startGit = "start /B git clone --progress";
		pclose(popen($startGit." ".$_POST["git"]." ".$userProjectRoot.$gitName." 2>".$relativeToUserRoot."\\proj.log", "w"));
		pclose(popen($startGit." ".$amirGit." ".$DebuugerRoot."Debugger 2>".$relativeToUserRoot."\\Debugger.log", "w"));
		file_put_contents($relativeToUserRoot."/goD.sh", "#!/bin/bash\n tail -n 1 Debugger.log");
		file_put_contents($relativeToUserRoot."/goG.sh", "#!/bin/bash\n tail -n 1 proj.log");
		$returnJson['status'] = 0;
		$returnJson['message'] = "wait 5 minites and check clone";
		echo json_encode((object)$returnJson);
	}elseif ($_POST["task"]=="check git"){
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
		}
		echo json_encode((object)$returnJson);
	}elseif ($_POST["task"]=="add version") {
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
	}elseif ($_POST["task"]=="run Python") {
		$opts = array(
  			'http'=>array(
    			'method'=>"GET",
    			'header'=>"Accept-language: en\r\n" .
              	"Cookie: foo=bar\r\n"
  			)
		);
		$context = stream_context_create($opts);
		// Open the file using the HTTP headers set above
		$file = file_get_contents("http://local.test/users/".$folderNmae."/index.php", false, $context);
		//echo($file);
	}elseif ($_POST["task"]=="run Pom") {
		$str = $userProjectRoot.$gitName."\\".$pomPath;
		exec("dir /s /b " .$str."\*pom.xml* > ".$relativeToUserRoot."\\log.txt");
		$arr = explode("\n",file_get_contents($relativeToUserRoot."\\log.txt"));
		for ($i=0; $i < sizeof($arr) ; $i++) { 
			set_time_limit(20);
			//pastPom($arr[$i],"C:\\Users\\pc-home\\Desktop\\Github\\users\\".$folderNmae."\\rootLearn\\Debugger\\my-app\\target\\uber-my-app-1.0.1-SNAPSHOT.jar","C:\\Users\\pc-home\\Desktop\\Github\\users\\".$folderNmae."\\path.txt");
			$pathForJar = $userProjectRoot.$gitName."\\".$pomPath."\\uber-my-app-1.0.1-SNAPSHOT.jar";
			$pathForPathtx =$userProjectRoot.$gitName."\\".$pomPath."\\path.txt";
			pastPom($arr[$i],$pathForJar,$pathForPathtx);
		}
	}elseif ($_POST["task"]=="clean mvn"){
		$relativeToPoomRoot = $relativeToUserRoot."\\rootLearn\\Debugger\\my-app";
		exec("mvn -f ".$relativeToPoomRoot." clean install");
	}elseif ($_POST["task"]=="mvn install"){
		exec("mvn -f ../users/".$folderNmae."/rootLearn/Debugger/my-app install");
	}elseif($_POST["task"]=="pathTxt"){
		$strPom = $userProjectRoot.$gitName."\\".$pomPath;
		$str = $mavenroot."\r\n".$userProjectRoot.$gitName;
		file_put_contents($strPom."\\path.txt",$str);
		$oldTarget = $DebuugerRoot.'\\Debugger\\my-app\\target\\uber-my-app-1.0.1-SNAPSHOT/jar';
		$newTarget = $userProjectRoot.$gitName."\\".$pomPath."\\uber-my-app-1.0.1-SNAPSHOT.jar";
		rename($oldTarget, $oldTarget);
	}elseif($_POST["task"]=="run java"){
		echo("mvn -f ..\\users\\".$folderNmae."\\rootGit\\".$gitName."\\".$pomPath." install");
		//pclose(popen("start /B mvn -f ../users/".$folderNmae."/rootGit/".$gitName."/".$pomPath, "w"));		
	}
	
	//echo json_encode((object)$returnJson);
?>
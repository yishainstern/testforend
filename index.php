
<?php
	header('Content-Type: application/json');
	error_reporting(E_ALL);
	$returnJson = array();
	$gitName = $_POST["gitName"];
	$folderNmae = $_POST["id"]; 
	if ($_POST["task"]=='open folder'){
		if ((is_dir('users/'.$folderNmae)==TRUE)){
			$returnJson['status'] = 1;
			$returnJson['message'] = "there is a folder like this alredy, pick a new name";
		}else{
			if (is_dir('../users/'.$folderNmae)||is_dir('users/'.$folderNmae))
			{
    			$returnJson['status'] = 2;
				$returnJson['message'] = "Faild to creat the folder for".$folderNmae;
			}else {
				mkdir('../users/'.$folderNmae, 0777, true);
				chmod('../users/'.$folderNmae, 0777);
				mkdir('users/'.$folderNmae, 0777, true);
				chmod('users/'.$folderNmae, 0777);
				mkdir('../users/'.$folderNmae.'/rootGit', 0777, true);
				chmod('../users/'.$folderNmae.'/rootGit', 0777);
				mkdir('../users/'.$folderNmae.'/rootBugs', 0777, true);
				chmod('../users/'.$folderNmae.'/rootBugs', 0777);
				mkdir('../users/'.$folderNmae.'/rootLearn', 0777, true);
				chmod('../users/'.$folderNmae.'/rootLearn', 0777);
				mkdir('../users/'.$folderNmae.'/out', 0777, true);
				chmod('../users/'.$folderNmae.'/out', 0777);
				$returnJson['status'] = 0;
				$returnJson['message'] = "create folder,".$folderNmae."lets clone :)";	
			}
		}
	}elseif ($_POST["task"]=='clone git') {
		pclose(popen("start /B git clone --progress ".$_POST["git"]." ../users/".$folderNmae.'/rootGit/'.$gitName." 2>../users/".$folderNmae."/proj.log", "w"));
		pclose(popen("start /B git clone --progress https://github.com/amir9979/Debugger.git ../users/".$folderNmae."/rootLearn/Debugger 2>../users/".$folderNmae."/Debugger.log", "w"));
		$returnJson['status'] = 0;
		$returnJson['message'] = "wait a minite ad check clone";
	}elseif ($_POST["task"]=="check git"){
		//$x = array();
		//$ans = myShowdir('users/'.$folderNmae,'');
		//var_dump($ans);
	}elseif ($_POST["task"]=="add version") {
		//var_dump($_FILES);
		if($_FILES["csvFile"]["error"]==UPLOAD_ERR_OK){
			$tmp_name = $_FILES["csvFile"]["tmp_name"];
        	// basename() may prevent filesystem traversal attacks;
        	// further validation/sanitation of the filename may be appropriate
        	$name = basename($_FILES["csvFile"]["name"]);
        	$uploadDir = "../users/".$folderNmae."/rootBugs";
        	move_uploaded_file($tmp_name, "$uploadDir/$name");
        	chmod("../users/".$folderNmae."/rootBugs/".$name, 0777);
        	$str = 'workingDir=C:\\Users\\pc-home\\Desktop\\Github\\users\\'.$folderNmae."\\out"."\r\n";
        	$str = $str."git=C:\\Users\\pc-home\\Desktop\\Github\\users\\".$folderNmae."\\rootGit\\".$gitName."\r\n";
        	$str = $str."bugs=C:\\Users\\pc-home\\Desktop\\Github\\users\\".$folderNmae."\\rootBugs\\".$name."\r\n";
        	$str = $str."vers=(". $_POST["ver"].")";
        	file_put_contents("../users/".$folderNmae."/rootLearn/Debugger/learner/antConf.txt",$str); 
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
	}
	
	echo json_encode((object)$returnJson);
?>
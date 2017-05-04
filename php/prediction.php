<?php
	






	function prepare_pridction($folderRoot,$userProjectRoot,$runingRoot,$outputPython){
		chdir($userProjectRoot);
		exec("dir DebuggerTests /s /b > ".$runingRoot."DebuggerTests.txt");
		if (!file_exists($outputPython.'\\DebuggerTests')){
			mkdir($outputPython.'\\DebuggerTests', 0777, true);
		}
		$arr = explode("\n",file_get_contents($runingRoot."DebuggerTests.txt"));
		if (sizeof($arr)>0){
			for ($i=0; $i < sizeof($arr); $i++) { 
				$command = 'start /B xcopy '.$arr[$i].' '.$outputPython.'\\DebuggerTests /-Y';
				pclose(popen($command, "w"));
			}
		}
		$obj = update_progress('prepare_prediction', get_project_details($folderRoot),TRUE,$folderRoot);
		$returnJson['project'] = $obj;
		$returnJson['status'] = 111;
		$returnJson['message'] = "ready to run prediction";
		return $returnJson;
	}	
	function run_pridction($folderRoot){
		chdir($learnDir);
		$command = 'start /B python wrapper.py antConf.txt experiments 2>ee.log';
		pclose(popen($command, "w"));
		$obj = update_progress('run_prediction', get_project_details($folderRoot),TRUE,$folderRoot);
		$returnJson['project'] = $obj;
		$returnJson['status'] = 111;
		$returnJson['message'] = "starting to test...check in 20 mintes what's doing";
		return $returnJson;
	}
	function get_pridction($folderRoot,$learnDir){
		chdir($learnDir);
		$file = 'antBugs.csv';
		if (file_exists($file)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="'.basename($file).'"');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: '.filesize($file));
		    readfile($file);
		    exit;
		}
		
		/*
		$obj = update_progress('get_prediction', get_project_details($folderRoot),TRUE,$folderRoot);
		$returnJson['project'] = $obj;
		$returnJson['status'] = 111;
		$returnJson['message'] = "starting to test...check in 20 mintes what's doing";
		return $returnJson;
		*/
	}		
		
?>
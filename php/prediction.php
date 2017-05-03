<?php
	function prepare_pridction($folderRoot,$userProjectRoot,$runingRoot){
		chdir($userProjectRoot);
		exec("dir DebuggerTests /AD /s> ".$runingRoot."\\DebuggerTests.txt");
		$arr = explode("\n",file_get_contents($runingRoot."\\DebuggerTests.txt"));
		if (sizeof($arr)>0){
			for ($i=0; $i < $arr; $i++) { 
				$command = 'start /B XCOPY '.$arr[$i].' '.$runingRoot;
				pclose(popen($command, "w"));
			}
		}
		$obj = update_progress('prepare_prediction', get_project_details($folderRoot),TRUE,$folderRoot);
		$returnJson['project'] = $obj;
		$returnJson['status'] = 111;
		$returnJson['message'] = "starting to test...check in 20 mintes what's doing";
		return $returnJson;
	}	
	function run_pridction($folderRoot){
		$obj = update_progress('run_prediction', get_project_details($folderRoot),TRUE,$folderRoot);
		$returnJson['project'] = $obj;
		$returnJson['status'] = 111;
		$returnJson['message'] = "starting to test...check in 20 mintes what's doing";
		return $returnJson;
	}
	function get_pridction($folderRoot){
		$obj = update_progress('get_prediction', get_project_details($folderRoot),TRUE,$folderRoot);
		$returnJson['project'] = $obj;
		$returnJson['status'] = 111;
		$returnJson['message'] = "starting to test...check in 20 mintes what's doing";
		return $returnJson;
	}		
		
?>
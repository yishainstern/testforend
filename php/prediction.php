<?php

	function prepare_pridction($details_obj){
		chdir($details_obj->userProjectRoot);
		exec("dir DebuggerTests /s /b > ".$details_obj->runingRoot."\\DebuggerTests.txt");
		if (!file_exists($details_obj->outputPython.'\\DebuggerTests')){
			mkdir($details_obj->outputPython.'\\DebuggerTests', 0777, true);
		}
		$arr = explode("\n",file_get_contents($details_obj->runingRoot."\\DebuggerTests.txt"));
		$str = "";
		if (sizeof($arr)>0){
			for ($i=0; $i < sizeof($arr); $i++) { 
				if (strlen($arr[$i])>0){
					$tmp = substr($arr[$i], 0,(strlen($arr[$i])-1));
					$str .= "start /B xcopy ".$tmp." ".$details_obj->outputPython."\\DebuggerTests /-Y\n";
				}
			}
		}
		return $str;
	}	
	function run_pridction($details_obj){
		$command = "cd ".$details_obj->learnDir."\n";
		$command .= "python wrapper.py antConf.txt experiments 2>pred.log\n";
		return $command;
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

	function all_pred($details_obj){
		$obj = json_decode(file_get_contents($details_obj->folderRoot.'\\project_details.json'));
		$obj->details->progress->mille_stones->end_testing->flag = true;
		$str = prepare_pridction($details_obj);
		$obj->details->progress->mille_stones->run_prediction->flag = true;
		$str .= run_pridction($details_obj);
		file_put_contents('filename',$str);
		run_cmd_file($details_obj,$str,"runpred","all_done");
		
	}

	function results($details_obj){
		$obj = json_decode(file_get_contents($details_obj->folderRoot.'\\project_details.json'));
		$arr =  scandir($details_obj->outputPython."\\weka");
		$ans = array();
		$ans['status'] = "111";
		$ans['message'] = "get files";
		$ans['files'] = $arr;
		return $ans;

	}
		
?>
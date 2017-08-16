<?php
	function get_file_info($details_obj){
		$ans = array();
		chdir("files");
		if ($details_obj->which_output=="prediction"){
			if (is_file($details_obj->file_name_output)){
				$ans["status"] = 1;
				$ans["info"] = file_get_contents($details_obj->file_name_output);
			}else {
				$ans["status"] = 2;
				$ans["info"] = "no information";	
			}

		}else{
			if(!strpos($details_obj->file_name_output, "barinelOptA")===FALSE){
				$ans["status"] = 1;
				$ans["info"] = file_get_contents("barinelOptA.csv");
			}elseif (!strpos($details_obj->file_name_output, "plannerResall")===FALSE){
				$ans["status"] = 1;
				$ans["info"] = file_get_contents("plannerResall.csv");
			}else{
				$ans["status"] = 2;
				$ans["info"] = "no information";	
			}
		}
		return $ans;
	}
	function prepare_pridction($details_obj){
		$tmp_path = $details_obj->project->runingRoot."\\DebuggerTests.txt";
		chdir($details_obj->project->folderRoot);
		exec("dir DebuggerTests /s /b > ".$tmp_path);
		if (!file_exists($details_obj->project->outputPython.'\\DebuggerTests')){
			mkdir($details_obj->project->outputPython.'\\DebuggerTests', 0777, true);
		}
		$arr = explode("\n",file_get_contents($tmp_path));
		$str = "";
		if (sizeof($arr)>0){
			for ($i=0; $i < sizeof($arr); $i++) { 
				if (strlen($arr[$i])>0){
					$tmp = substr($arr[$i], 0,(strlen($arr[$i])-1));
					$str .= "start /B xcopy ".$tmp." ".$details_obj->project->outputPython."\\DebuggerTests /-Y\n";
				}
			}
		}
		return $str;
	}	
	function run_pridction($details_obj){
		$command = "cd ".$details_obj->project->learnDir."\n";
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
		    exit;//f
		}
	}		

	function all_pred($details_obj){
		var_dump($details_obj);
		$details_obj->project = update_project_list($details_obj->project,"end_testing",true);
		$details_obj->project = update_project_list($details_obj->project,"run_prediction",true);
		update_project_details($details_obj->project);
		$str = prepare_pridction($details_obj);
		$str .= run_pridction($details_obj);
		run_cmd_file($details_obj,$details_obj->project,$details_obj->user,$str,"runpred","all_done");
		
	}

	function results($details_obj){
		$tmp_arr = get_project_progress($details_obj);
		$ans = array();
		if ($tmp_arr['status']==111){
			$project = $tmp_arr['project']; 
			$arr =  scandir($project->outputPython."\\weka");
			$ans['status'] = 111;
			$ans['message'] = "get files";
			$ans['files'] = $arr;
		}else {
			$ans['status'] = 555;
		}
		
		return $ans;
	}
	function experiments($details_obj){
		$tmp_arr = get_project_progress($details_obj);
		$ans = array();
		$arrays = array();
		if ($tmp_arr['status']==111){
			$project = $tmp_arr['project']; 
			$ex = $project->outputPython."\\experiments";
			$arr =  scandir($ex);
			for ($i=0; $i < sizeof($arr); $i++) { 
				$tmp = $arr[$i];
				$tmp_path = $ex."\\".$tmp;
				if (is_dir($tmp_path)){
					$tmp_obj = new stdClass();
					$tmp_obj->dir_name = $tmp;
					$tmp_obj->dir_arr = array();
					$tmp_arr = scandir($tmp_path);
					for ($j=0; $j < sizeof($tmp_arr); $j++) { 
						$tmp_inner = $tmp_arr[$j];
						if ($tmp_inner=="barinelOptA.csv" || $tmp_inner=="plannerResall.csv"){
							array_push($tmp_obj->dir_arr, $tmp_obj->dir_name."\\".$tmp_inner);
						}
					}
					if (sizeof($tmp_obj->dir_arr)>0){
						array_push($arrays,$tmp_obj);
					}
				}
			}			
			$ans['status'] = 111;
			$ans['message'] = "get files";
			$ans['files'] = $arrays;
		}else {
			$ans['status'] = 555;	
		}	
		return $ans;
	}
	function get_file($details_obj){
		$tmp_arr = get_project_progress($details_obj);
		$ans = array();
		if ($tmp_arr['status']==111){
			$project = $tmp_arr['project']; 
			chdir($project->outputPython."\\".$details_obj->project->witch_folder);
			$file = $details_obj->project->witch_file;
			if (file_exists($file)) {
		    	header('Content-Description: File Transfer');
		    	header('Content-Type: application/octet-stream');
		    	header('Content-Disposition: attachment; filename="'.basename($file).'"');
		    	header('Expires: 0');
		    	header('Cache-Control: must-revalidate');
		    	header('Pragma: public');
		    	header('Content-Length: '.filesize($file));
		    	header('thename: '.basename($file));
		    	readfile($file);
		    	exit;
			}
			}else {
				//do nothins
			}

	}
	function all_done($details_obj){
		$details_obj->project = update_project_list($details_obj->project,"get_prediction",true);
		update_project_details($details_obj->project);
	}	
		
?>
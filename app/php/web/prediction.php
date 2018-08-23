<?php
	use Debugger\displayfile;
	use Debugger\compress;
	//Every file needs to have a description that explains its purpose, This function will not be called yet because the descriptions are not ready
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
			}else{
				$ans["status"] = 2;
				$ans["info"] = "no information";	
			}
		}
		return $ans;
	}
	//To run the diagnosis part we need the output of the maven part. This function takes the output of the Maven testing and copies it to a relevant place in the server.
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
	//Execute the diagnoses part.
	function run_pridction($details_obj){
		$command = "";
		//$command = "cd ".$details_obj->project->learnDir."\n";
		//$command .= "python wrapper.py antConf.txt experiments 2>pred.log\n";
		return $command;
	}
	//Get and download a file from the output of learning task.
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
	//Start the prediction part.
	function all_pred($details_obj){
		$details_obj->project = update_project_list($details_obj->project,"end_testing",true);
		$details_obj->project = update_project_list($details_obj->project,"run_prediction",true);
		update_project_details($details_obj->project);
		run_cmd_file($details_obj,$details_obj->project,$details_obj->user,'',"runpred","all_done");
	}
	//Get a list of all files in the weka folder (out put of the first part).
	function results($details_obj){
		$tmp_arr = get_project_progress($details_obj);
		$ans = array();
		if ($tmp_arr['status']==111){
			$project = $tmp_arr['project']; 
			$arr =  scandir($project->outputPython."\\web_prediction_results");
			$ans['status'] = 111;
			$ans['message'] = "get files";
			$ans['files'] = $arr;
		}else {
			$ans['status'] = 555;
		}
		return $ans;
	}
	//Get all files of diagnoses part.
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
					$tmp_obj = (object)(array());
					$tmp_obj->dir_name = $tmp;
					$tmp_obj->dir_arr = array();
					$tmp_arr = scandir($tmp_path);
					for ($j=0; $j < sizeof($tmp_arr); $j++) { 
						$tmp_inner = $tmp_arr[$j];
						if ($tmp_inner=="barinelOptA.csv"){
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
	//Send the file to the user.
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
	//Get a list of all json files in the weka folder (out put of the first part).
	function get_watch($details_obj){
		$tmp_arr = get_project_progress($details_obj);
		$ans = array();
		$project = $tmp_arr['project']; 
		$file_name = $project->outputPython."\\web_prediction_results\\".$details_obj->project->watch_file_name.".json";
		if ($tmp_arr['status']==111 &&  file_exists($file_name)){
			$ans["watch_obj"] = json_decode(file_get_contents($file_name));
		}else {
			$ans['status'] = 555;
		}
		return $ans;
	}
	//Send the file to the user.
	function display_file($details_obj){
		$tmp_arr = get_project_progress($details_obj);
		$ans = array();
		if ($tmp_arr['status']==111){
			$project = $tmp_arr['project']; 
			$displayfile = new displayfile($details_obj->project->witch_folder,$details_obj->project->witch_file);
			$displayfile->setproject($project);
			return $displayfile->prepareFileFormat();
		}else {
			//do nothins
		}
	}
	//Send the file to the user.
	function zip_file($details_obj){
		$tmp_arr = get_project_progress($details_obj);
		$ans = array();
		if ($tmp_arr['status']==111){
			$project = $tmp_arr['project']; 
			$compress = new compress($details_obj->project->witch_folder,$details_obj->project->witch_files);
			$compress->setproject($project);
			$tmp_ans = $compress->compress_files($project);
			$ans['status'] = 111;
			$ans['files'] = $tmp_ans;
			return $ans;
		}else {
			//do nothins
		}
	}
	//After the last task is done.
	function all_done($details_obj){
		$err_f = $details_obj->project->outputPython.'\\markers\\error_file';
		if (file_exists($err_f)){
			$details_obj->project->problem = true;
			update_project_details($details_obj->project);
		}else {
			$details_obj->project = update_project_list($details_obj->project,"get_prediction",true);
			update_project_details($details_obj->project);
		}
		
	}	
?>
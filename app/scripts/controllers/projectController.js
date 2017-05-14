'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('projectController', ['$scope', '$timeout', '$rootScope', '$interval', 'service','config','flow','$state', '$stateParams', function ($scope, $timeout, $rootScope, $interval, service, config, flow,$state, $stateParams) {
 	
 	service.intervalfunc(service);
 	if (!$stateParams.id){
 		$state.transitionTo('enter');
 	}
 	$scope.get_status = function(){

 	}
 	$scope.userArray = [
 		{
 			task:'sign & clone ',
 			title:'clone your project',
 			state:'',
 			description: 'this part will upload your git project to our server, follow the instroction',
 			flag:false,
 			status:"",
 			is_page:false
 		},
 		{
 			task:'details',
 			title:'give us all details for your project',
 			state:'',
 			description:'In this part we will run an AI algorithim to learn about your code, follow the instroction',
 			flag:false,
 			status:"",
 			page:true
 		},
		{
 			task:'prediction',
 			title:'get prediction',
 			state:'',
 			description:'In this part we will get a prediction for your part in the code' ,
 			flag:false,
 			status:"",
 			page:true
 		} 		
 	];
 	$scope.$on('project_object_exsites',function(){
 		$scope.tmp = $rootScope.project.details.progress.mille_stones;
 		if (!$scope.tmp.end_clone.flag){
 			$scope.userArray[0].status= 'still cloning';
 			$scope.userArray[0].flag= false;
 		}else {
 			$scope.userArray[0].status= 'finsied cloning go to next task';
 			$scope.userArray[0].flag= true;
 		}
 	});

 	$scope.get_state = function(str){
 		switch (str){
 			case 'create_folders':
 				return null;
 			break;
 			case 'start_clone':
 				return 'dashboard.clone';
 			break;
 			case 'end_clone':
 				return 'dashboard.clone';
 			break;
 			case 'upload_bug_file':
 				return 'dashboard.prepareOffline';
 			break;
 			case 'get_prediction':

 			break;
 			case 'start_offline':
 				return 'dashboard.runOffline';
 			break;
 			case 'start_testing':
 				return 'dashboard.prepareOnline';
 			break;
 			case 'end_testing':
 				return 'dashboard.prepareOnline';
 			break;
 			case 'end_offline':
 				return 'dashboard.runOffline';
 			break;
 			case 'pick_version':
 				return 'dashboard.prepareVersion';
 			break;
 			case 'check_version':
 				return 'dashboard.prepareVersion';
 			break;
 			case 'prepare_jar':
 				return 'dashboard.prepareVersion';
 			break; 	
 			case 'update_pom':
 				return 'dashboard.pomUpdate';
 			break;		 			
 			case 'prepare_mvn':
 				return 'dashboard.prepareOnline';
 			break;
 			case 'get_prediction':
 				return 'dashboard.prediction';
 			break;
 			case 'run_prediction':
 				return 'dashboard.prediction';
 			break;
 			case 'prepare_prediction':
 				return 'dashboard.prediction';
 			break; 			 			
 		}
 	}
//    
	$scope.task_page = function(state,params){
		$scope.task = state
		state = $scope.get_state(state);
		$state.transitionTo(state,{id:params,task:$scope.task});
	}

;}]);
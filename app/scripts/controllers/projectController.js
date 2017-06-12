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
 	$scope.show_loader = true;
 	$scope.how_much_done = 10;
 	if (!$stateParams.id){
 		$state.transitionTo('enter');
 	}
 	var p_interval;
 	$scope.get_status = function(){

 	}
 	$scope.userArray = [
 		{
 			task:'sign & clone ',
 			title:'clone your project',
 			description: 'this part will upload your git project to our server, follow the instroction',
 			flag:false,
 			status:"",
 			has_page:false
 		},
 		{
 			task:'details',
 			title:'give us all details for your project',
 			state: 'dashboard.all_details',
 			send:{task:'all_details'},
 			description:'In this part we will run an AI algorithim to learn about your code, follow the instroction',
 			flag:false,
 			status:"can not enter yet",
 			has_page:false
 		},
		{
 			task:'prediction',
 			title:'get prediction',
 			state:'dashboard.results',
 			description:'In this part we will get a prediction for your code' ,
 			send:{task:'get_results'},
 			flag:false,
 			status:"no results yet",
 			has_page:false
 		} 		
 	];
 	$scope.$on('project_object_exsites',function(){
 		$scope.show_loader = false;
 		$scope.tmp = $rootScope.project.details.progress.mille_stones;
 		if ($rootScope.project.details.problem){
 			alert("aborted testing because "+$rootScope.project.details.problem);
 		}
 		if (!$scope.tmp.end_clone.flag){
 			$scope.userArray[0].status= 'still cloning';
 			$scope.userArray[0].flag= false;
 		}else {
 			$scope.userArray[0].status= 'finsied cloning go to next task';
 			$scope.userArray[0].flag= true;
 			$scope.userArray[1].status= 'enter arev page';
 			$scope.userArray[1].has_page= true;
 			$scope.how_much_done = 30;
 		}
 		if (!$scope.tmp.start_offline.flag){
 			$scope.userArray[1].status= 'did not do it yet';
 			$scope.userArray[1].flag= false;
 		}else {
 			$scope.userArray[1].status= 'strrted learning';
 			$scope.userArray[1].flag= true;
 			$scope.userArray[1].has_page= false;
 			if ($scope.tmp.start_testing.flag){
 				$scope.how_much_done = 70;
 				$scope.userArray[1].status= 'strted maven testing';
 				$scope.userArray[2].status= 'has some details';
 				$scope.userArray[2].has_page= true;

 				if ($scope.tmp.get_prediction.flag){
 					$scope.how_much_done = 100;
 					$scope.userArray[2].status= 'has all details';
 					$scope.userArray[2].flag= true;
 				}
 			}
 		}
 		$timeout(function() {service.intervalfunc(service);}, 5000);
 	});
 	$scope.go_page = function(item){
 		if (item.send){
 			item.send.id = $rootScope.project.details.folderName;
 			item.send.user = $rootScope.user.details.userName;
 		}
 		$state.transitionTo(item.state,item.send);
 	}
 
//    
	$scope.task_page = function(state,params){
		$scope.task = state
		state = $scope.get_state(state);
		$state.transitionTo(state,{id:params,task:$scope.task});
	}

;}]);
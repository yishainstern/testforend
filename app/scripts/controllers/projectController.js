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
 	$scope.show_loader = false;
 	$scope.how_much_done = 10;
	$scope.open_details = true;
	$scope.options_visible = true;
	$scope.diagnosis_and_prediction_visible = false; 
	$scope.bug_mining_visible = false; 
 	var p_interval;
 	//Array of project progress.
 	$scope.userArray = [
 		{
 			task:'Sign & Clone',
 			title:'Clone your project.',
 			description: 'This part tells you if we uploaded your git project to our server, follow the instructions.',
 			flag:false,
 			status:"",
 			has_page:false
 		},
 		{
 			task:'Details',
 			title:'Project details',
 			state: 'dashboard.all_details',
 			send:{task:'all_details'},
 			description:'Press here to get to the page where you give us all details we need for your project. After that, we will run an AI algorithm to learn about your code, follow the instructions.',
 			flag:false,
 			status:"Can not enter yet!",
 			has_page:false
 		},
		{
 			task:'Prediction & Diagnosis',
 			title:'Get prediction',
 			state:'dashboard.results',
 			description:'Get the output of our AI learning on your code, this part can take a lot of time (4-12 houres), please be patient.' ,
 			send:{task:'get_results'},
 			flag:false,
 			status:"No results yet",
 			has_page:false
 		} 		
 	];
 	//Go to the page of the task that is described.
 	$scope.go_page = function(item){
		$state.transitionTo('dashboard.diagAndPred',{id:$rootScope.project.folderName});
	}
	//Go to the page of the task that is described.
	$scope.go_page_bug_mine = function(item){
		$state.transitionTo('dashboard.bugMine',{id:$rootScope.project.folderName});
	}
	 //Go to the page of the task that is described.
 	$scope.bug_mine = function(){
		$scope.bug_mining_visible  =true
		$scope.diagnosis_and_prediction_visible  =false
		$scope.options_visible  =false
	}
	$scope.diagnose_and_predict = function(){
		$scope.bug_mining_visible  =false
		$scope.diagnosis_and_prediction_visible  =true
		$scope.options_visible  =false
	}
	$scope.back_to_options = function(){
		$scope.bug_mining_visible  =false
		$scope.diagnosis_and_prediction_visible  =false
		$scope.options_visible  =true
	}
	$scope.is_option_visible = function(){
		return $scope.options_visible
	}
	$scope.is_bug_mining_visible = function(){
		return $scope.bug_mining_visible 
	}
	$scope.is_diagnosis_and_prediction_visible = function(){
		return $scope.diagnosis_and_prediction_visible 
	}
;}]);
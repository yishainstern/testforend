'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('projectController', ['$scope', '$timeout', '$rootScope', '$interval', 'service','config','flow','$state', '$stateParams', function ($scope, $timeout, $rootScope, $interval, service, config, flow,$state, $stateParams) {
 	$scope.show_loader = false;
 	$scope.how_much_done = 10;
	$scope.open_details = true;
	$scope.options_visible = true;
	$scope.diagnosis_and_prediction_visible = false; 
	$scope.bug_mining_visible = false; 
 	var p_interval;
	 $scope.$on('project_object_exsites',function(){
		$scope.show_loader = false;
	});
 	//Go to the page of the task that is described.
 	$scope.go_page = function(item){
		$rootScope.task = 'diag_and_pred'
		$state.transitionTo('dashboard.diagAndPred',{id:$stateParams.id});
	}
	//Go to the page of the task that is described.
	$scope.go_page_bug_mine = function(item){
		$rootScope.task = 'bug_mining'
		$state.transitionTo('dashboard.bugMine',{id:$stateParams.id});
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
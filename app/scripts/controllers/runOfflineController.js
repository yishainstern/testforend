'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('runOfflineController', ['$scope', '$timeout', '$rootScope', '$interval', 'service','config','$state', '$stateParams', function ($scope, $timeout, $rootScope, $interval, service, config, $state, $stateParams) {
 	
 	service.intervalfunc(service);
 	if (!$stateParams.id){
 		$state.transitionTo('enter');
 	}

 
 	$scope.start = function(){
        $scope.run_success = false;
        service.ajaxfunc('run_Python','run-offline',false)
        .then(function(data){
        	data = JSON.parse(data);
        	console.log(data);
        	$scope.run_success = true;
        	$scope.display_run_text = data.message;
        },function(data){}); 		
 	}

 	$scope.check = function(){
        $scope.run_success = false;
        service.ajaxfunc('check_Python','run-offline',false)
        .then(function(data){
        	data = JSON.parse(data);
        	console.log(data);
        	$scope.run_success = true;
        	$scope.display_run_text = data.message;
        	$rootScope.project = data.project;
        },function(data){}); 		
 	}


;}]);
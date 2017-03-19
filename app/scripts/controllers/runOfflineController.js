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
        service.ajaxfunc('run_Python','run-offline',false)
        .then(function(data){console.log(JSON.parse(data));},
            function(data){$scope.fail_new_project(data);}); 		
 	}
;}]);
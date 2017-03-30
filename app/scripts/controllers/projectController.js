'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('projectController', ['$scope', '$timeout', '$rootScope', '$interval', 'service','config','$state', '$stateParams', function ($scope, $timeout, $rootScope, $interval, service, config, $state, $stateParams) {
 	
 	service.intervalfunc(service);
 	if (!$stateParams.id){
 		$state.transitionTo('enter');
 	}

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
 			case 'end_testing':

 			break;
 			case 'start_offline':
 				return 'dashboard.runOffline';
 			break;
 			case 'start_testing':

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

 		}
 	}
//    
	$scope.task_page = function(state,params){
		state = $scope.get_state(state);
		$state.transitionTo(state,{id:params});
	}

;}]);
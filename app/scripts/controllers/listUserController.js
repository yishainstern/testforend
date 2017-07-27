'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('listUserController', ['$scope', '$timeout', '$rootScope','service','config','$state','$stateParams', function ($scope, $timeout, $rootScope, service,config,$state,$stateParams) {
	$scope.show_loader = true;
	$rootScope.call_user();
	//event after user was loded from server.
    $scope.$on('user_in',function(){
		$scope.show_loader = false;
	});
    //change state to make a new project
    $scope.go_to_new_project = function(){
        $state.transitionTo('dashboard.newProject',{user:$rootScope.user.details.userName});
    }
    //change state to project page.
    $scope.go_to_project = function(name){
    	$state.transitionTo('dashboard.project',{id:name,user:$rootScope.user.details.userName});
    }

;}]);
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
    $scope.show_mask ={
        flag:false
    }
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
    //remove project from server
    $scope.remove_project = function(item){
        var data_to_send = new FormData();
        data_to_send.append('userName',$rootScope.user.details.userName);
    }
;}]);
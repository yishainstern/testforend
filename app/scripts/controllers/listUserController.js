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
    $scope.picked_item = {};
    $scope.show_mask ={
        flag:false
    }
	$rootScope.call_user();
	//Event after user was loded from server.
    $scope.$on('user_in',function(){
		$scope.show_loader = false;
	});
    //change state to make a new project
    $scope.go_to_new_project = function(){
        $state.transitionTo('dashboard.newProject',{user:$rootScope.user.userName});
    }
    //change state to project page.
    $scope.go_to_project = function(name){
    	$state.transitionTo('dashboard.project',{id:name,user:$rootScope.user.userName});
    }
    //remove project from server.
    $scope.remove_project = function(){
        var data_to_send = new FormData();
        data_to_send.append('userName',$rootScope.user.userName);
        data_to_send.append('id',$scope.picked_item.name);
        service.ajaxfunc('remove_project',true,data_to_send)
        .then(function(data){
            data = $rootScope.checkJson(data);
            if (data.status==111){
                $rootScope.user = data.user;
            }
        },
        function(data){

        });
    }
    //Prepare to remove project.
    $scope.prepare_trash = function(item){
         $scope.picked_item = item;
    }
    //Do not remove project.
    $scope.clear_p = function(){
        $scope.picked_item = {};
    }
;}]);
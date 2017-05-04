'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('cloneController', ['$scope', '$timeout', '$rootScope','service','config','$state', '$stateParams', function ($scope, $timeout, $rootScope, service,config,$state, $stateParams) {
 	service.intervalfunc(service);
 	$scope.new_clone_error=false;
 	$scope.new_clone_success=false;
    $scope.to_disable = false;
    $scope.clone_details = {};
    $scope.yishaifill = {
        name: 'ant',
        url: 'https://git-wip-us.apache.org/repos/asf/ant.git'
    }

    $scope.could_check_clone = false;

    $scope.autoFill = function(){
        $scope.clone_details.gitUrl = $scope.yishaifill.url;
        $scope.clone_details.projectName = $scope.yishaifill.name;
    }

    $scope.success_clone = function(obj){
    	if (obj.status==111){
    		$rootScope.project = obj.project;
    		$scope.new_clone_success = true;
    		$scope.display_clone_text = obj.message;
    	}
    }

    $scope.success_check = function(obj){
    	if (obj.status==111){
    		$rootScope.project = obj.project;
    		$scope.new_clone_success = true;
    		$scope.display_clone_text = obj.message;
    	}else if(obj.status == 1){
    		$scope.new_clone_error = true;
    		$scope.display_clone_text = obj.message;
    		$scope.try_agin = true;
    		$rootScope.project = obj.project;
    	}else if(obj.status == 2){
    		$scope.new_clone_error = true;
    		$scope.display_clone_text = obj.message;
    		$scope.try_agin = true;
    		$rootScope.project = obj.project;
    	}
    }

    $scope.success_try = function(obj){
        if (obj.status==111){
            $rootScope.project = obj.project;
            $scope.new_clone_success = true;
            $scope.display_clone_text = obj.message;
        }
    }

    $scope.clone_project = function(){
    	if(!$rootScope.project.details.gitName||!$rootScope.project.details.gitUrl){
    		$scope.new_clone_success = false;
    		$scope.new_clone_error = true;
    		$scope.display_clone_text = 'all fileds are reqiured';
    		return;
    	}
    	$scope.new_clone_error = false;
    	service.ajaxfunc('clone_git','clone-project',false)
        .then(function(data){$scope.success_clone(JSON.parse(data));},
            function(data){$scope.fail_new_project(data);});
    }

    $scope.check_clone = function(){
    	$scope.new_clone_error = false;
    	$scope.new_clone_success = false;
    	service.ajaxfunc('check_clone','clone-project',false)
        .then(function(data){$scope.success_check(JSON.parse(data));},
            function(data){$scope.fail_new_project(data);});    	
    }

    $scope.try_agin = function(){
        $scope.new_clone_error = false;
        $scope.new_clone_success = false;
        service.ajaxfunc('try_agin','clone-project',false)
        .then(function(data){$scope.success_try(JSON.parse(data));},
            function(data){$scope.fail_new_project(data);});          
    }

}]);
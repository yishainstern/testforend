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
    $scope.clone_details = {};
    $scope.yishaifill = {
        name: 'ant',
        url: 'https://git-wip-us.apache.org/repos/asf/ant.git'
    }

    $scope.autoFill = function(){
        $scope.clone_details.gitUrl = $scope.yishaifill.url;
        $scope.clone_details.projectName = $scope.yishaifill.name;
    }

    $scope.clone_project = function(){
    	service.ajaxfunc('clone_git','clone-project',false)
        .then(function(data){$scope.success_new_project(JSON.parse(data));},
            function(data){$scope.fail_new_project(data);});
    }

;}]);
'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('newProjectController', ['$scope', '$timeout', '$rootScope','service','config','$state', function ($scope, $timeout, $rootScope, service,config,$state) {
    $scope.project_details = {};
    $scope.new_project_error = false;
    $scope.new_project_success = false;
    $scope.display_new_project_text = '';
    $scope.yishaifill = {
        root: 'sternyi',
        name: 'ant',
        url: 'https://git-wip-us.apache.org/repos/asf/ant.git',
        discription: 'this is a discription'
    }

    $scope.autoFill = function(){
        $scope.project_details.project_description = $scope.yishaifill.discription;
        $scope.project_details.gitUrl = $scope.yishaifill.url;
        $scope.project_details.projectName = $scope.yishaifill.name;
        $scope.project_details.folderName = $scope.yishaifill.root;
    }

    $scope.success_new_project = function(data){
        if (data.status==111){
            $state.transitionTo('dashboard.project');
//            service.ajaxfunc('clone_git','new-project',false)
//            .then(function(data){$scope.success_clone(data);}, 
//            function(data){$scope.fail_clone(data);});
        }
    }

    $scope.fail_new_project = function(data){

    }

    $scope.success_clone = function(data){
        $scope.display_new_project_text = 'press the check buttin in 5 minites and check if clone is done';    
        $scope.new_project_error = false;
        $scope.new_project_success = true;
    }

    $scope.check_clone = function(){

    }

    $scope.fail_clone = function(data){

    }        

    $scope.create_new_project = function(){
        service.ajaxfunc('open_folder','new-project',false)
        .then(function(data){$scope.success_new_project(data);},
            function(data){$scope.fail_new_project(data);});
    }
    $scope.project_details.userName = localStorage.getItem('name');

;}]);
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
    $scope.page_error = false;
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
        if (data && data.status==111){
            $rootScope.user = data.user;
            $rootScope.project = data.project;
            $state.transitionTo('dashboard.project',{id:$rootScope.project.details.folderName});
//            service.ajaxfunc('clone_git','new-project',false)
//            .then(function(data){$scope.success_clone(data);}, 
//            function(data){$scope.fail_clone(data);});
        }else if (data && data.status==1){
            $scope.display_new_project_text = 'this name already exits...try a diffrent name';    
            $scope.new_project_error = true;
            $scope.new_project_success = false;            
        }else if (data && data.status==2){
            $scope.display_new_project_text = data.message;    
            $scope.new_project_error = true;
            $scope.new_project_success = false;            
        }
    }

    $scope.fail_new_project = function(data){

    }

    $scope.success_clone = function(data){

    }

    $scope.check_clone = function(){

    }

    $scope.fail_clone = function(data){

    }        

    $scope.create_new_project = function(){
        $scope.display_new_project_text = "";
        $scope.new_project_error = false;  
        if (!$scope.new_id || !$scope.new_description || !$scope.new_url || !$scope.new_name){
             $scope.page_error = true;
             $scope.display_new_project_text = 'all fileds are requried';
             return;
        }
        service.ajaxfunc('open_folder','new-project',false)
        .then(function(data){$scope.success_new_project(JSON.parse(data));},
            function(data){$scope.fail_new_project(data);});
    }
;}]);
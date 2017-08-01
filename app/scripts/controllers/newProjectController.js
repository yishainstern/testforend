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
    $scope.did_start = false;
    $scope.new_project_error = false;
    $scope.new_project_success = false;
    $scope.page_error = false;
    $scope.display_new_project_text = '';
    $rootScope.project = {
    };
    $scope.show_loader = true;
    $rootScope.call_user();
    //event after user was loded from server.
    $scope.$on('user_in',function(){
        $scope.show_loader = false;
    });
    //Callback after a new project was created in servers memory.
    $scope.success_new_project = function(data){
        $scope.show_loader = false;
        if (data && data.status==111){
            $rootScope.user = data.user;
            $rootScope.project = data.project;
            $state.transitionTo('dashboard.project',{id:$rootScope.project.folderName});
        }else if (data && data.status==1){
            $scope.display_new_project_text = 'This "project-name" already exists...try a different name.';    
            $scope.new_project_error = true;
            $scope.new_project_success = false; 
            $scope.did_start = false;         
        }else if (data && data.status==2){
            $scope.display_new_project_text = data.message;    
            $scope.new_project_error = true;
            $scope.new_project_success = false;  
            $scope.did_start = false;          
        }
    }
    //Callback when the new project was not created in servers memory.
    $scope.fail_new_project = function(data){
        $scope.did_start = false;
    }
    //Run an HTTP request to create a new project with the details that the user inserted.
    $scope.create_new_project = function(){
        $scope.show_loader = true;
        $scope.did_start = true;
        $scope.display_new_project_text = "";
        $scope.new_project_error = false;  
        if (!$scope.new_id || !$scope.new_description || !$scope.new_url || !$scope.new_name){
             $scope.page_error = true;
             $scope.new_project_error = true    ;
             $scope.display_new_project_text = "All fields are required.";
             $scope.did_start = false;
             $scope.show_loader = false;
             return;
        }
        service.ajaxfunc('open_folder','new-project',false)
        .then(function(data){$scope.success_new_project(JSON.parse(data));},
            function(data){$scope.fail_new_project(data);});
    }
;}]);
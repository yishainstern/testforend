'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('all_details', ['$scope', '$timeout', '$rootScope','service','config','$state', '$stateParams','$interval', function ($scope, $timeout, $rootScope, service,config,$state, $stateParams, $interval) {
    service.intervalfunc(service);
    $scope.file = false;
    $scope.upload_error = false;
    $scope.upload_success = false;
    $scope.display_upload_text = '';
    $scope.uploadFile = function(files) {
        $scope.file = files[0];
    }
    $scope.success_tag = function(obj){
        if (obj.status == 111){
            $rootScope.project = obj.project;
        }
    }
    $scope.$on('project_object_exsites',function(){
       if (!$rootScope.project.details.tags){
            var ff = new FormData();
            ff.append('id',$rootScope.project.details.folderName);
            ff.append('userName',$rootScope.user.details.userName);
            service.ajaxfunc('get_tags','get_tags',ff).then(
                function(data){$scope.success_tag(JSON.parse(data));},
                function(data){$scope.fail_tag(data);}
            );      
       }
    });
    $scope.upload_files = function(){
        $scope.upload_error = false;
        $scope.upload_success = false;
        if (!$scope.file|| $rootScope.project.details.ver){
            $scope.upload_error = true;
             $scope.display_upload_text = 'all fileds are required';
        }else {

        }
        service.ajaxfunc('add_version','files',false)
        .then(function(data){
            data = JSON.parse(data);
            if (data.status==111){
                $scope.upload_success = true;
                $scope.display_upload_text = data.message;
            }
        },function(data){

        });        
    }

;}]);
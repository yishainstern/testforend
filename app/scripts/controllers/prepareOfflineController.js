'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('prepareOfflineController', ['$scope', '$timeout', '$rootScope','service','config','$state', '$stateParams', function ($scope, $timeout, $rootScope, service,config,$state, $stateParams) {
    service.intervalfunc(service);
    $scope.file = false;
    $scope.upload_error = false;
    $scope.upload_success = false;
    $scope.display_upload_text = '';
    $scope.uploadFile = function(files) {
        $scope.file = files[0];
    }

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
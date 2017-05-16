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
    $scope.optionsList = [];
    $scope.optionsList2 = [];
    $scope.list = [];
    $scope.only_one = false;
    $scope.sss='';
    $scope.display_upload_text = '';
    $scope.ff = function(){
        $scope.sss='sss';
        console.log($scope.optionsList);
    }
    $scope.afterSelectItem = function(arr, item){
     arr.push(item);   
    }
    $scope.afterSelectItem2 = function(arr, item){
        if (arr.length>0){
            $scope.only_one = true;

        }else {
             $scope.false = true;
            arr.push(item);  
        }
    }

    $scope.uploadFile = function(files) {
        $scope.file = files[0];
    }
    $scope.arr = ['a','b'];
    $scope.success_tag = function(obj){
        if (obj.status == 111){
            $rootScope.project = obj.project;
            $scope.list = $rootScope.project.details.tags;
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
       }else {
              $scope.list = $rootScope.project.details.tags;
       }
    });
    $scope.upload_details = function(){
        $scope.upload_error = false;
        if (!$rootScope.project.details.pomPath||!$scope.file||$scope.optionsList.length<1||$scope.optionsList2.length<1){
            $scope.display_text="all files are reqiured";
            $scope.upload_error = true;
            return;
        }

        var tt = $rootScope.project.details.pomPath;
        if (tt.includes("/")){
            $scope.display_text="change all of your / marks to \\ ";
            $scope.upload_error = true;
            return;
        }
        alert('ready');
        return;
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
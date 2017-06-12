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
    $scope.show_loader = true;
    $scope.succ_counter = 0;
    $scope.optionsList = [];
    $scope.optionsList2 = [];
    $scope.list = [];
    $scope.poms = [];
    $scope.only_one = false;
    $scope.sss='';
    $scope.pom_root = "";
    $scope.display_upload_text = '';
    $scope.did_start= false;
    $scope.ff = function(){
        $scope.sss='sss';
        console.log($scope.optionsList);
    }
    $scope.afterSelectItem = function(arr, item){
     arr.push(item);   
    }
    $scope.afterSelectItem2 = function(arr, item){
        if (arr.length>0){
            arr[0] = item;
        }else {

            arr.push(item);  
        }
         $scope.pom_root = item;
    }
//ddfd
    $scope.uploadFile = function(files) {
        $scope.file = files[0];
    }
    $scope.arr = ['a','b'];
    $scope.success_tag = function(obj){
        $scope.succ_counter++;
        if ($scope.succ_counter==2){
            $scope.show_loader = false;
        }
        if (obj.status == 111  && obj.tags){
            $scope.list = JSON.parse(obj.tags);
        }
    }
    $scope.success_pom = function(obj){
        $scope.succ_counter++;
        if ($scope.succ_counter==2){
            $scope.show_loader = false;
        }
        if (obj.status == 111  && obj.poms){
            $scope.poms = JSON.parse(obj.poms);
        }
    }
    $scope.$on('project_object_exsites',function(){
        var ff = new FormData();
        ff.append('id',$rootScope.project.details.folderName);
        ff.append('userName',$rootScope.user.details.userName);
        service.ajaxfunc('get_tags','get_tags',ff).then(
            function(data){$scope.success_tag(JSON.parse(data));},
            function(data){$scope.fail_tag(data);}
        );
        service.ajaxfunc('get_poms','get_tags',ff).then(
            function(data){$scope.success_pom(JSON.parse(data));},
            function(data){$scope.fail_pom(data);}
        );
    });
    $scope.upload_details = function(){
        $scope.show_loader = true;
        $scope.did_start= true;
        $scope.upload_error = false;
        if ($scope.optionsList.length<2||!$rootScope.project.details.bugzilla_url||!$rootScope.project.details.bugzilla_product || !$scope.pom_root){
            $scope.display_text="all files are reqiured";
            $scope.upload_error = true;
            $scope.did_start= false;
            $scope.show_loader = false;
            return;
        }
        var form = document.forms.namedItem('get_tags');
        var data_to_send = new FormData(form);
        data_to_send.append('testVersion',$scope.optionsList[$scope.optionsList.length-2]);
        var tmp_str = "";
        for (var i=0 ; i<$scope.optionsList.length;i++){
            if (i<($scope.optionsList.length-1)){
                tmp_str = tmp_str + $scope.optionsList[i] +",";        
            }else {
                tmp_str = tmp_str + $scope.optionsList[i];
            }
        }
        if ($scope.pom_root=="pom.xml"){
            $scope.pom_root = "";
        }else {
            $scope.pom_root = $scope.pom_root.substring(0,($scope.pom_root.length-9));
        }
        data_to_send.append('all_versions',tmp_str);
        data_to_send.append('pomPath',$scope.pom_root);
        service.ajaxfunc('all_details','files',data_to_send)
        .then(function(data){
            data = JSON.parse(data);
            if (data.status==111){
                $scope.show_loader = false;
                $state.transitionTo('dashboard.project',{id:$rootScope.project.details.folderName,user:$rootScope.user.details.userName});
                $scope.upload_success = true;
                $scope.display_upload_text = data.message;
                if (data.project){
                    $rootScope.project = data.project;
                    $scope.did_start= true;
                }
            }
        },function(data){

        });        
    }

;}]);
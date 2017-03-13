'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('enterController', ['$scope', '$timeout', '$rootScope','service','config', '$state', function ($scope, $timeout, $rootScope, service,config,$state) {
    $scope.login_details = {};
    $scope.sginup_details = {};
    $scope.rong_password = false;
    $scope.sign_up_error = '';
    $scope.succsess = '';
    $scope.sign_text = '';
    $scope.error_sign = false;
    $scope.display_sgin_text = 'משתמש כבר קיים';

    $scope.show_error = function(str){
        $scope.display_sgin_text = str;
        $scope.error_sign = true;
    }

    $scope.success_sgin = function(data){
        data = JSON.parse(data);
        console.log(data);
        if (data && data.status && data.status==111){
            $rootScope.current_name = $scope.sginup_details.userName; 
            $rootScope.current_password = $scope.sginup_details.password;
            localStorage.setItem('name',$rootScope.current_name);
            localStorage.setItem('password',$rootScope.current_password);
            $state.transitionTo('dashboard.listUser');
        }else if (data && data.status && data.status==1){
            $scope.show_error('user alredy exsists');
        }
    }

    $scope.fail_sgin = function(data){
        $scope.show_error('some failer..try agin');        
        console.log(data);
    }

    $scope.sgin_task = function(form_naame){
        if (!$scope.sginup_details.userName || !$scope.sginup_details.password){
            $scope.show_error('both fileds are required');        
            return;
        }
        $scope.display_sgin_text = '';        
        $scope.error_sign = false;
        service.ajaxfunc('sgin_up',form_naame,false)
        .then(function(data){$scope.success_sgin(data);},
            function(data){$scope.fail_sgin(data);});
    }

;}]);
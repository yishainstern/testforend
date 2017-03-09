'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('enterController', ['$scope', '$timeout', '$rootScope','service','config', function ($scope, $timeout, $rootScope, service,config) {
    $scope.login_details = {};
    $scope.sginup_details = {};
    $scope.rong_password = false;
    $scope.sign_up_error = '';
    $scope.succsess = '';
    $scope.sign_text = '';
    $scope.error_sign = false;
    $scope.display_sgin_text = 'משתמש כבר קיים';
    $scope.sgin_task = function(form_naame){
        if (!$scope.sginup_details.userName || !$scope.sginup_details.password){
            $scope.display_sgin_text = 'both fileds are required';        
            $scope.error_sign = true;
            return;
        }
        $scope.display_sgin_text = '';        
        $scope.error_sign = false;
        service.ajaxfunc('sgin_up',form_naame,false).then(function(data){
            data = JSON.parse(data);
            console.log(data);
            if (data && data.status && data.status==111){
                $rootScope.current_name = $scope.sginup_details.userName; 
                $rootScope.current_password = $scope.sginup_details.password;
                $state.transitionTo('listUser');
            }
        },function(data){
            $scope.display_sgin_text = 'some failer..try agin';        
            $scope.error_sign = true;            
            console.log(data);
        });
    }

;}]);
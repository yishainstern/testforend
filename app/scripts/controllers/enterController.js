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
    $scope.sgin_task = function(){
        if (!$scope.sginup_details.userName || !$scope.sginup_details.password){
            $scope.display_sgin_text = 'אחד השדןת ריק';        
            $scope.error_sign = true;
            return
        }
        service.ajaxfunc('sgin_up').then(function(data){

        },function(data){

        });
    }

;}]);
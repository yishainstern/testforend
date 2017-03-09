'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('listUserController', ['$scope', '$timeout', '$rootScope','service','config', function ($scope, $timeout, $rootScope, service,config) {
    $scope.form_data = new FormData();
    $scope.form_data.append('userName',$rootScope.current_name);
    $scope.form_data.append('password',$rootScope.current_password);
    service.ajaxfunc('get_user_list','',).then(function(data){
        data = JSON.parse(data);
        console.log(data);
        if (data && data.status && data.status==111){
            $state.transitionTo('listUser');
        }
    },function(data){
        $scope.display_sgin_text = 'some failer..try agin';        
        $scope.error_sign = true;            
        console.log(data);
    });

;}]);
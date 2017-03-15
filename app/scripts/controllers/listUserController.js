'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('listUserController', ['$scope', '$timeout', '$rootScope','service','config','$state', function ($scope, $timeout, $rootScope, service,config,$state) {
    $scope.form_data = new FormData();
    if (!$rootScope.current_name || !$rootScope.current_password){
        $rootScope.current_name = localStorage.getItem('name');
        $rootScope.current_password = localStorage.getItem('password');
    }    
    $scope.form_data.append('userName',$rootScope.current_name);
    $scope.form_data.append('password',$rootScope.current_password);
    service.ajaxfunc('get_user_list','',$scope.form_data).then(function(data){
        $rootScope.dd = [{name: "hh"},{name: "wwtttt"}];
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

    $scope.go_to_new_project = function(){
        $state.transitionTo('dashboard.newProject');
    }


;}]);
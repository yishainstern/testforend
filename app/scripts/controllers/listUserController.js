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
    if (!$rootScope.user.details.name || !$rootScope.user.details.password){
        $rootScope.user.details.name = localStorage.getItem('name');
        $rootScope.user.details.password = localStorage.getItem('password');
    }    
    $scope.form_data.append('userName',$rootScope.user.details.name);
    $scope.form_data.append('password',$rootScope.user.details.password);
    service.ajaxfunc('get_user_list','',$scope.form_data).then(function(data){
        $rootScope.dd = [{name: "hh"},{name: "wwtttt"}];
        data = JSON.parse(data);
        console.log(data);
        if (data && data.status && data.status==111){
            
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
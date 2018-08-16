'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('listAdminUserController', ['$scope', '$timeout', '$rootScope','service','config','$state','$stateParams', function ($scope, $timeout, $rootScope, service,config,$state,$stateParams) {
    $scope.users_data= [];
    $scope.show_mask ={
        flag:false
    }
    //change state to make a new project
    $scope.go_to_users_data = function(){
        $state.transitionTo('dashboard.usersDataTables',{user:$rootScope.user.userName});
    }
    //create the users data table
    $scope.get_users_data_table = function(){
        service.ajaxfunc('get_users_data','',false)
        .then(function(data){$scope.success_create_users_data(data);},
            function(data){$scope.fail_create_users_data(data);});
    }
    
    $scope.fail_create_users_data = function(data){
        $scope.show_error('Failes to retrieve users data','create_users_data_table'); 
    }
    //builds the table from the data
    $scope.success_create_users_data = function(data){
        $scope.users_data =data;
    }
;}]);
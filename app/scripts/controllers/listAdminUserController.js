'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('listAdminUserController', ['$scope', '$timeout', '$rootScope','service','config','$state','$stateParams', function ($scope, $timeout, $rootScope, service,config,$state,$stateParams) {
    $scope.show_mask ={
        flag:false
    }
    //change state to make a new project
    $scope.go_to_users_data = function(){
        $state.transitionTo('dashboard.usersDataTables',{user:$rootScope.user.userName});
    }
;}]);
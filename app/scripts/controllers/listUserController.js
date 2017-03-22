'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('listUserController', ['$scope', '$timeout', '$rootScope','service','config','$state', function ($scope, $timeout, $rootScope, service,config,$state) {


    $scope.go_to_new_project = function(){
        $state.transitionTo('dashboard.newProject');
    }

    $scope.go_to_project = function(name){
    	$state.transitionTo('dashboard.project',{id:name});
    }

;}]);
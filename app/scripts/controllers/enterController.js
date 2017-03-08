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
;}]);
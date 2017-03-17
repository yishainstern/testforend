'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('prepareOfflineController', ['$scope', '$timeout', '$rootScope','service','config','$state', '$stateParams', function ($scope, $timeout, $rootScope, service,config,$state, $stateParams) {
    service.intervalfunc(service);
    $scope.upload_files = function(){
        
        service.ajaxfunc('add_version','files',false)
        .then(function(data){consol.log(JSON.parse(data));},
            function(data){});        
    }

;}]);
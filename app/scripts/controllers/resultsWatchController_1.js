'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('resultsWatchController_1', ['$scope', '$timeout', '$rootScope','service','config','$state', '$stateParams', '$interval', function ($scope, $timeout, $rootScope, service,config,$state, $stateParams, $interval) {
 	service.intervalfunc(service);
    $scope.witch_file_name;
    $('.horizontal .progress-fill span').each(function(){
        var percent = $(this).html();
        $(this).parent().css('width', percent);
    });

    $('.vertical .progress-fill span').each(function(){
        var percent = $(this).html();
        var pTop = 100 - ( percent.slice(0, percent.length - 1) ) + "%";
        $(this).parent().css({
            'height' : percent,
            'top' : pTop
        });
    });
}]);
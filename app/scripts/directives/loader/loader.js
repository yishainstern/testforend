'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp')
    .directive('loader',['$state', function($state){
        return {
            templateUrl:'scripts/directives/loader/loader.html',
            restrict: 'E',
            replace: true,
            link:function(scope, element, attrs) {
            },
            controller:function ($state, $rootScope,$scope){
                
            }
        }
    }]);



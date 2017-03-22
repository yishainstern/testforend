'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp')
    .directive('goToList',['$state', function($state){
        return {
            templateUrl:'scripts/directives/goToList/goToList.html',
            restrict: 'E',
            replace: true,
            link:function(scope, element, attrs) {
                scope.page_title = attrs.title;
            },
            controller:function ($state, $rootScope,$scope){
                $scope.project_page = function(){
                    if ($rootScope.project.details.name){
                        $state.transitionTo('dashboard.project',{id:$rootScope.project.details.name});  
                    }
                }
            }
        }
    }]);



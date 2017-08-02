'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */

angular.module('sbAdminApp')
  .directive('sidebar',['$location','$state' ,function($rootScope,$state) {
    return {
      templateUrl:'scripts/directives/sidebar/sidebar.html',
      restrict: 'E',
      replace: true,
      /*scope: {
      },*/
      controller:function($scope,$rootScope,$state){
        $scope.ff = $rootScope;
        $scope.selectedMenu = 'dashboard';
        $scope.collapseVar = 0;
        $scope.multiCollapseVar = 0;
        $scope.check = function(x){
          if(x==$scope.collapseVar)
            $scope.collapseVar = 0;
          else
            $scope.collapseVar = x;
        };
        $scope.project_list = function(){
          $state.transitionTo('dashboard.listUser',{user:$rootScope.user.userName});
        }
        $scope.add_project = function(){
          $state.transitionTo('dashboard.newProject',{user:$rootScope.user.userName});
        }
        $scope.multiCheck = function(y){
          
          if(y==$scope.multiCollapseVar)
            $scope.multiCollapseVar = 0;
          else
            $scope.multiCollapseVar = y;
        };
      }
    }
  }]);

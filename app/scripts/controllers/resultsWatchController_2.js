'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('resultsWatchController_2', ['$scope', '$timeout', '$rootScope','service','config','$state', '$stateParams', '$interval', function ($scope, $timeout, $rootScope, service,config,$state, $stateParams, $interval) {
   service.intervalfunc(service);
   //Get the project folder hirarchy
   $scope.data = $stateParams.task._sub_packages;
   /* $scope.$on('$viewContentLoaded', function() {
    var data_to_send = new FormData();
    data_to_send.append('watch_file_name',$stateParams.task);
    service.ajaxfunc('get_watch','watch',data_to_send)
    .then(function(data){
        if (data.status == "111"){
            $scope.data = data.watch_file;
        }
    },function(data){alert('bad')});
  }); */
   
    $scope.remove = function (scope) {
        scope.remove();
      };

      $scope.toggle = function (scope) {
        scope.toggle();
      };

      $scope.moveLastToTheBeginning = function () {
        var a = $scope.data.pop();
        $scope.data.splice(0, 0, a);
      };

      $scope.newSubItem = function (scope) {
        var nodeData = scope.$modelValue;
        nodeData.nodes.push({
          id: nodeData.id * 10 + nodeData.nodes.length,
          title: nodeData.title + '.' + (nodeData.nodes.length + 1),
          nodes: []
        });
      };

      $scope.collapseAll = function () {
        var a = $stateParams.task;
        $scope.$broadcast('angular-ui-tree:collapse-all');
      };

      $scope.expandAll = function () {
        $scope.$broadcast('angular-ui-tree:expand-all');
      };

      
      //Show test of error or success.
    $scope.show_error = function(str,flag){
  }
}]);
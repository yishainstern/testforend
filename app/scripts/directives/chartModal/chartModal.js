'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp')
    .directive('chartmodel',['$state', function($state){
        return {
            templateUrl:'scripts/directives/chartModal/chartModal.html',
            restrict: 'E',
            replace: true,
            link:function(scope, element, attrs) {
            },
            controller:function ($state, $rootScope,$scope){
                $scope.charts = [];
                $scope.pos = {alg : {},times : {}};
                $scope.line = {
                    times: [],
                    alg: [],
                    data: [
                      
                    ],
                    onClick: function (points, evt) {
                      console.log(points, evt);
                    }
                };
                $scope.$on('chartdb',function(data,args){
                    $scope.chartdb = {};
                    $scope.chartdb = args.data;
                    $scope.chartdb.charts=[];
                });
                $scope.displayChart = function(){
                    console.log($scope.chartdb);
                    $scope.line.times = [];
                    $scope.line.alg = [];
                    $scope.line.data = [];
                    for (var i = 0; i < $scope.chartdb.values.length; i++) {
                        if ($scope.chartdb.values[i].type=="main"){
                            for (var j = 0; j < $scope.chartdb.values[i].values.length; j++){
                                if($scope.chartdb.values[i].flags[j]){
                                    if ($scope.chartdb.values[i].name=="algorithm"){
                                        $scope.line.alg[$scope.line.alg.length] = $scope.chartdb.values[i].values[j];
                                        $scope.pos.alg[$scope.chartdb.values[i].values[j]]=$scope.line.alg.length-1;
                                    } else if ($scope.chartdb.values[i].name=="times"){
                                        $scope.line.times[$scope.line.times.length] = $scope.chartdb.values[i].values[j];
                                        $scope.pos.times[$scope.chartdb.values[i].values[j]]=$scope.line.times.length-1;
                                    }
                                }
                            }
                        }
                    }
                    console.log($scope.pos);
                    for (var i = 0; i < $scope.chartdb.values.length; i++) {
                        if ($scope.chartdb.charts[i]==true){
                            $scope.charts[$scope.charts.length] = angular.copy($scope.line);
                        }
                    }
                }
                
                
            }
        }
    }]);



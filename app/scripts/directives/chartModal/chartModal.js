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
                    $scope.charts = [];
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
                    for (var t = 0; t < $scope.line.alg.length; t++) {
                        $scope.line.data[t] = [];
                        for (var tt = 0; tt < $scope.line.times.length; tt++) {
                            $scope.line.data[t][tt]=0;
                        }
                    }
                    for (var i = 0; i < $scope.chartdb.values.length; i++) {
                        if ($scope.chartdb.charts[i]==true){
                            $scope.charts[$scope.chartdb.values[i].name] = {};
                            var tmp = $scope.charts[$scope.chartdb.values[i].name];
                            tmp.name = $scope.chartdb.values[i].name;
                            tmp.chart = angular.copy($scope.line); 
                            
                        }
                    }
                    for (var rr = 0; rr < $scope.chartdb.raws.length; rr++) {
                        var arr = Object.entries($scope.chartdb.raws[rr]);
                        for (var rrr = 0; rrr < arr.length; rrr++) {
                            var ttmp = arr[rrr];
                            var tmp_chart = $scope.charts[ttmp[0]];
                            if (tmp_chart){
                                try{
                                    var tt_rr = $scope.pos.alg[$scope.chartdb.raws[rr].algorithm];
                                    var tt_co = $scope.pos.times[$scope.chartdb.raws[rr].times];
                                    if(tt_rr && tt_co){tmp_chart.chart.data[tt_rr][tt_co]=ttmp[1]};
                                }catch(e){
                                    console.log("ss");
                                }
                            }
                        }
                    }
                    console.log($scope.charts);
                }
            }
        }
    }]);



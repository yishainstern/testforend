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
                $scope.height = Math.floor(window.innerHeight*0.7);
                $scope.width = Math.floor(window.innerWidth*0.5);
                $scope.idcanvas = "myChart";
                $scope.first_chart = true;
                $scope.second_chart = false;
                $scope.last_chart = {};
                var ctx = {};
                var ctx2 ={};
                $scope.colorArr = ['rgba(28, 200, 28, 0.8)', 'rgba(255, 99, 132, 0.8)','rgba(37, 79, 248, 0.8)','rgba(164, 13, 190, 0.8)','rgba(164, 226, 8, 0.8)'];
                var showall = {};
                var config = {
                    type: 'bar',
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July"],
                        datasets: [{
                            label: "My First dataset",
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            data: [
                                20,
                                30,
                                40,
                                50,
                                60,
                                70,
                                80
                            ],
                            fill: false,
                        }, {
                            label: "My Second dataset",
                            fill: false,
                            backgroundColor: 'rgba(255, 99, 10, 0.2)',
                            borderColor: 'rgba(255, 99, 10, 0.2)',
                            data: [
                                20,
                                40,
                                40,
                                50,
                                60,
                                20,
                                80
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                        title:{
                            display:true,
                            text:'are chart'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Times'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Score'
                                }
                            }]
                        }
                    }
                };                
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
                    if (window.myLine){
                        window.myLine.destroy();
                        window.myLine = null;
                    }
                    if (window.myBar){
                        window.myBar.destroy();
                    }
                    $scope.view_charts= false;
                    $scope.chartnames = [];
                    $scope.first_chart = true;
                    $scope.second_chart = false;
                });
                $scope.downloadcanvas = function(){
                    var ff = document.getElementById($scope.idcanvas);
                    var link = document.createElement('a');
                    link.innerHTML = 'download image';
                    link.addEventListener('click', function(ev) {
                        link.href = ff.toDataURL();
                        link.download = "mypainting.png";
                    }, false);
                    document.body.appendChild(link);
                    link.click();
                }
                $scope.chagechart = function () {
                    ctx = document.getElementById("myChart").getContext("2d");
                    ctx2 = document.getElementById("myChart2").getContext("2d");
                    if (window.myLine){
                        window.myLine.destroy();
                        window.myLine = null;
                    }
                    if (window.myBar){
                        window.myBar.destroy();
                        window.myBar = null;
                    }
                    $scope.first_chart = !$scope.first_chart;
                    $scope.second_chart = !$scope.second_chart;
                    if ($scope.first_chart){
                        $scope.idcanvas = "myChart";
                        showall.type = 'bar';
                        window.myBar = new Chart(ctx, showall);
                    }else{
                        $scope.idcanvas = "myChart2";
                        showall.type = 'line';
                        window.myLine = new Chart(ctx2, showall);
                    }
                    $('.modal-open .modal').animate({
                        scrollTop: $("#scroll_to_me").offset().top
                    }, 2000);
                }
                $scope.show_chart = function(item){
                    ctx = document.getElementById("myChart").getContext("2d");
                    ctx2 = document.getElementById("myChart2").getContext("2d");
                    showall = $scope.charts[item];
                    $scope.last_chart = showall;                   
                    if (window.myLine){
                        window.myLine.destroy();
                        window.myLine = null;
                    }
                    if (window.myBar){
                        window.myBar.destroy();
                        window.myBar = null;
                    }
                    if ($scope.first_chart){
                        showall.type = 'bar';
                        window.myBar = new Chart(ctx, showall);
                    }else{
                        showall.type = 'line';
                        window.myLine = new Chart(ctx2, showall);    
                    }
                }
                $scope.displayChart = function(){
                    $scope.charts = [];
                    config.data.labels = [];
                    config.data.datasets = [];
                    $scope.view_charts= false;
                    $scope.charts__arr= [];
                    for (var i = 0; i < $scope.chartdb.values.length; i++) {
                        if ($scope.chartdb.values[i].type=="main"){
                            for (var j = 0; j < $scope.chartdb.values[i].values.length; j++){
                                if($scope.chartdb.values[i].flags[j]){
                                    if ($scope.chartdb.values[i].name=="algorithm"){
                                        config.data.datasets[config.data.datasets.length] = {label:$scope.chartdb.values[i].values[j],fill:false, backgroundColor: $scope.colorArr[j%$scope.colorArr.length],borderColor: $scope.colorArr[j%$scope.colorArr.length],data:[]};
                                        $scope.pos.alg[$scope.chartdb.values[i].values[j]]=config.data.datasets.length-1;
                                    } else if ($scope.chartdb.values[i].name=="times"){
                                        config.data.labels[config.data.labels.length] = $scope.chartdb.values[i].values[j];
                                        $scope.pos.times[$scope.chartdb.values[i].values[j]]=config.data.labels.length-1;
                                    }
                                }
                            }
                        }
                    }
                    for (var t = 0; t < config.data.datasets.length; t++) {
                        config.data.datasets[t].data = [];
                        for (var tt = 0; tt < config.data.labels.length; tt++) {
                            config.data.datasets[t].data[tt]=0;
                        }
                    }
                    for (var i = 0; i < $scope.chartdb.values.length; i++) {
                        if ($scope.chartdb.charts[i]==true){
                            $scope.charts[$scope.chartdb.values[i].name] = angular.copy(config);
                            $scope.charts[$scope.chartdb.values[i].name].options.title.text =  $scope.chartdb.values[i].name +' chart';       
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
                                    if(tt_rr >= 0 && tt_co >= 0){
                                        var tmp_float_num = parseFloat(ttmp[1]);
                                        tmp_float_num = tmp_float_num.toFixed(2);
                                        var tmp_arr = ['auc_avg','recall_avg','precision_avg'];
                                        if (tmp_arr.indexOf(ttmp[0])>=0) {
                                            tmp_float_num = Math.floor(tmp_float_num*100);
                                        }
                                        tmp_chart.data.datasets[tt_rr].data[tt_co]=tmp_float_num;
                                    }
                                }catch(e){
                                    
                                }
                            }
                        }
                    }
                    $scope.chartnames = Object.keys($scope.charts);
                    $scope.view_charts= true;
                }
            }
        }
    }]);



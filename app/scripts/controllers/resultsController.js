'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('resultsController', ['$scope', '$timeout', '$rootScope','service','config','$state', '$stateParams', '$interval', function ($scope, $timeout, $rootScope, service,config,$state, $stateParams, $interval) {
 	service.intervalfunc(service);
    $scope.witch_file_name;
    $scope.show_loader = true;
    $scope.load_count = 0;
    var t_stop;
    var p_stop = $interval(function() {
        if (typeof Swiper == 'function' && $('.swiper-container').length > 0 && $('.swiper-pagination').length > 0 && $('.swiper-slide').length > 0){
            //stop interval
            $interval.cancel(p_stop);
            p_stop = undefined;
            var swiper = new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                paginationClickable: true,
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev',
                spaceBetween: 30
            });            
        }else {
            //keep it alive
        }
    },300);
    $scope.files = [];
    $scope.get_file = function(item,folder){
         $scope.show_loader = true;
        var form = document.forms.namedItem('results');
        var data_to_send = new FormData(form);
        data_to_send.append('witch_file',item);
        data_to_send.append('witch_folder',folder);
        service.filefunc('get_file','results',data_to_send)
        .then(function(data){
                 $scope.show_loader = false;
            },function(data){alert('bad'); $scope.show_loader = false;}
            );  
    }
    $scope.get_res = function(){
        t_stop = $interval(function() {
        if ($rootScope.user.details.userName && $rootScope.project.details.folderName && $rootScope.project.details.gitName){
            //stop interval
            $interval.cancel(t_stop);
            t_stop = undefined;
            service.ajaxfunc('get_output','results',false)
            .then(function(data){
                $scope.load_count ++;
                if ($scope.load_count==2){
                    $scope.show_loader = false;
                }
                data = $rootScope.checkJson(data);
                if (data.status == "111"){
                    $scope.files = data.files;
                }
            },function(data){alert('bad')});
            service.ajaxfunc('get_experiments','results',false)
            .then(function(data){
                $scope.load_count ++;
                if ($scope.load_count==2){
                    $scope.show_loader = false;
                }
                data = $rootScope.checkJson(data);
                if (data.status == "111"){
                    $scope.experiments = data.files;
                    console.log($scope.experiments);
                }
            },function(data){alert('bad')}); 
        }else {
            //keep it alive
        }
    },300);
        
    }
    
        
    $scope.get_file_info = function(item){
        $scope.witch_file_name = item;
    }
}]);
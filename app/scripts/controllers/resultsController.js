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
    $scope.info_txt = {txt:""};
    $scope.a_counter = 0;
    var t_stop;
    var swiper;
    var p_stop = $interval(function() {
        if (typeof Swiper == 'function' && $('.swiper-container').length > 0 && $('.swiper-pagination').length > 0 && $('.swiper-slide').length > 0){
            //stop interval
            $interval.cancel(p_stop);
            p_stop = undefined;
            swiper = new Swiper('.swiper-container', {
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
    $scope.swipe_res = function(index){
        if (typeof Swiper == 'function'){
            swiper.slideTo(index);
        }
    }
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
         
    $scope.get_file_info = function(flag,item,index){
        $scope.show_loader = true;
        $scope.witch_file_name = item;
        var form = document.forms.namedItem('results');
        var data_to_send = new FormData(form);
        data_to_send.append('file_name_output',item);
        data_to_send.append('which_output',flag);
        service.ajaxfunc('get_file_info','results',data_to_send)
            .then(function(data){
                data = $rootScope.checkJson(data);
                $scope.show_loader = false;
                if ((data.status==1||data.status==2)&& data.info){
                     $scope.info_txt.txt = data.info;
                }
                $($('.togglt_trigger')[index]).click();        
            },function(data){
                alert('try agin');
                $scope.show_loader = false;
            }); 
    }
}]);
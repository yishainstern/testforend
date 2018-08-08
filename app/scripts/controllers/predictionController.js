'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('predictionController', ['$scope', '$timeout', '$rootScope','service','config','$state', '$stateParams', '$interval', function ($scope, $timeout, $rootScope, service,config,$state, $stateParams, $interval) {
 	service.intervalfunc(service);
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

    $scope.success_prediction_prepare = function(){
        console.log('done');
    }
    $scope.error_preperations = function(){
        console.log('done');
    }
    $scope.last_preperations = function(){
        service.ajaxfunc('prepare_pridction','change-version',false)
        .then(function(data){
            data = $rootScope.checkJson(data);
            if (data != null){
                $scope.success_prediction_prepare(data);    
            } 
        },function(data){
           $scope.error_preperations(data);
        });
    }
    $scope.run_pridction = function(){
        service.ajaxfunc('run_pridction','change-version',false)
        .then(function(data){
            data = $rootScope.checkJson(data);
            if (data != null){
                $scope.success_run_pridction(data);    
            } 
        },function(data){
           $scope.error_run_pridction(data);
        });
    }

    $scope.get_pridction = function(){
        service.filefunc('get_pridction','change-version',false)
        .then(function(data){
             
        },function(data){
           
        });
    }    
   
}]);
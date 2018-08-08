'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('prepareOnlineController', ['$scope', '$timeout', '$rootScope','service','config','$state', '$stateParams', '$interval', function ($scope, $timeout, $rootScope, service,config,$state, $stateParams, $interval) {
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

    $scope.success_preperations = function(){
        console.log('done');
    }
    $scope.error_preperations = function(){
        console.log('done');
    }
    $scope.last_preperations = function(){
        service.ajaxfunc('last_preperations','change-version',false)
        .then(function(data){
            data = $rootScope.checkJson(data);
            if (data != null){
                $scope.success_preperations(data);    
            } 
        },function(data){
           $scope.error_preperations(data);
        });
    }
    $scope.run_maven = function(){
        service.ajaxfunc('run_maven','change-version',false)
        .then(function(data){
            data = $rootScope.checkJson(data);
            if (data != null){
                $scope.success_preperations(data);    
            } 
        },function(data){
           $scope.error_preperations(data);
        });
    }
    $scope.success_check_maven = function(){
        console.log('done');
    }
    $scope.error_check_maven = function(){
        console.log('done');
    }
    $scope.check_maven = function(){
        service.ajaxfunc('maven_done','change-version',false)
        .then(function(data){
            data = $rootScope.checkJson(data);
            if (data != null){
                $scope.success_check_maven(data);    
            } 
        },function(data){
           $scope.error_check_maven(data);
        });
    }    
   
}]);
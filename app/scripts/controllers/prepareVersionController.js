'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('prepareVersionController', ['$scope', '$timeout', '$rootScope','service','config','$state', '$stateParams', '$interval', function ($scope, $timeout, $rootScope, service,config,$state, $stateParams, $interval) {
    service.intervalfunc(service);
    $scope.error_obj = {}
    $scope.error_obj.validation_error = false;
    $scope.error_obj.display_text = '';
    $scope.error_obj.pick_success = false;
    $scope.error_obj.pick_error = false;   
    $scope.initErrorsSwiper = function(){
        $timeout(function() {
            $scope.error_obj.validation_error = false;
            $scope.error_obj.display_text = '';
            $scope.error_obj.pick_success = false;
            $scope.error_obj.pick_error = false;
        }, 0);
    } 
    $scope.initErrors = function(){
            $scope.error_obj.validation_error = false;
            $scope.error_obj.display_text = '';
            $scope.error_obj.pick_success = false;
            $scope.error_obj.pick_error = false;
    }
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
                spaceBetween: 30,
                onSlideChangeStart: $scope.initErrorsSwiper
            }); 
            $scope.how_much_to_slide = 0;
            switch ($stateParams.task){
                case 'pick_version':
                    $scope.how_much_to_slide = 0;
                break;
                case 'check_version':
                    $scope.how_much_to_slide = 1;
                break;
                case 'prepare_jar':
                    $scope.how_much_to_slide = 2;
                break;
            }
            if ($scope.how_much_to_slide>0){
                swiper.slideTo($scope.how_much_to_slide);
            }           
        }else {
            //keep it alive
        }
    },300);
    $scope.success_version = function(data){
        if (data.status==111){
            $rootScope.project = data.project;
            $scope.error_obj.display_text = data.message;
            $scope.error_obj.pick_success = true;
        }else if (data.status==1){
            $scope.error_obj.display_text = data.message;
            $scope.error_obj.validation_error = true;    
            $scope.error_obj.pick_error = true;
        }
    }
    $scope.change_version = function(){
        $scope.initErrors();    
        if(!$rootScope.project.details.testVersion){
            $scope.error_obj.validation_error = true;
            $scope.error_obj.display_text = 'filed required';
            return;
        }else{
            service.ajaxfunc('change_version','change-version',false)
            .then(function(data){
                data = $rootScope.checkJson(data);
                if (data != null){
                    $scope.success_version(data);    
                } 
            },function(data){
               $scope.error_version(data);
            });
        }
    }
    $scope.check_version = function(){
        $scope.initErrors();
        service.ajaxfunc('check_version','change-version',false)
        .then(function(data){
            data = $rootScope.checkJson(data);
                if (data != null){
                    $scope.success_version(data);    
                } 
            },function(data){
               $scope.error_version(data);
            });
    }
    $scope.prepare_jar = function(){
        $scope.initErrors();
        service.ajaxfunc('create_jar','change-version',false)
        .then(function(data){
            data = $rootScope.checkJson(data);
                if (data != null){
                    $scope.success_version(data);    
                } 
            },function(data){
               $scope.error_version(data);
            });        
    }
}]);
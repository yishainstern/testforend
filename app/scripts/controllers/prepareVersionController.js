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
    $scope.validation_error = false;
    $scope.success_version = function(data){
        if (data.status==111){
            $rootScope.project = data.project;
            $scope.display_text = data.message;
            $scope.pick_success = true;
        }else if (data.status==1){
            $scope.display_text = data.message;
            $scope.validation_error = true;    
        }
    }    
    $scope.initErrors = function(){
        $scope.validation_error = false;
        $scope.display_text = '';
        $scope.pick_success = false;
        $scope.pick_error = false;
    }
    $scope.change_version = function(){
        $scope.initErrors();    
        if(!$rootScope.project.details.testVersion){
            $scope.validation_error = true;
            $scope.display_text = 'filed required';
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
    $scope.$on('project_object_exsites', function(event, args) {
        
    });
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
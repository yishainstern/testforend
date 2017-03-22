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

    $scope.change_version = function(){
        $scope.validation_error = false;
        $scope.display_text = '';
        if(!$rootScope.project.details.testVersion){
            $scope.validation_error = true;
            $scope.display_text = 'filed required';
        }else{
            service.ajaxfunc('chenge_version','clone-project',false)
            .then(function(data){
                $scope.success_check(JSON.parse(data));
            },function(data){
                $scope.fail_new_project(data);
            });
        }
    }
;}]);
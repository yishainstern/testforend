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
    $scope.get_file = function(item){
        var form = document.forms.namedItem('results');
        var data_to_send = new FormData(form);
        data_to_send.append('witch_file',item);
        service.ajaxfunc('get_file','results',data_to_send)
        .then(function(data){
                
            },function(data){alert('bad')}
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
                data = $rootScope.checkJson(data);
                if (data.status == "111"){
                    $scope.files = data.files;
                    console.log($scope.files);
                }
            },function(data){alert('bad')}
            );      
        }else {
            //keep it alive
        }
    },300);
        
    }
    
        
   
}]);
'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('pomController', ['$scope', '$timeout', '$rootScope','service','config','$state', '$stateParams', '$interval', function ($scope, $timeout, $rootScope, service,config,$state, $stateParams, $interval) {
 	service.intervalfunc(service);
 	$scope.initVars = function(){
 		$scope.display_text="";
 		$scope.update_error = false;
 		$scope.update_success = false; 		
 	}
 	$scope.initVars();

 	$scope.success_pom = function(data){
 		if (data.status==111 && data.data){
 			$scope.display_text="we updated your pom files...look to see witch files we updated";
 			$scope.update_success = true;
 			$scope.table = data.data;
 		}else if (data.status==1){
 			$scope.display_text="you do not have the right pom files in your project";
 			$scope.update_error = true;
 		}
 	}

 	$scope.updare_pom = function(){
 		$scope.initVars();
 		if (!$rootScope.project.details.pomPath){
 			$scope.display_text="all feilds required";
 			$scope.update_error = true;
 			return;
 		}
 		var tt = $rootScope.project.details.pomPath;
 		if (tt.includes("/")){
 			$scope.display_text="change all of your q marks to \\ ";
 			$scope.update_error = true;
 			return;
 		}
        service.ajaxfunc('update_pom','updare-pom',false)
        .then(function(data){
            data = $rootScope.checkJson(data);
            if (data != null){
                $scope.success_pom(data);    
            } 
        },function(data){
           $scope.error_version(data);
        }); 		
 	}
}]);
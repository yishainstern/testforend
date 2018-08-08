'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp')
	.directive('header',[ '$rootScope', function($rootScope){
		return {
        	templateUrl:'scripts/directives/header/header.html',
        	restrict: 'E',
        	replace: true,
        	controller:function($scope,$rootScope){
        		var c= 6;
        	}
    	}
	}]);



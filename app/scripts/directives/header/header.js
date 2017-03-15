'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp')
	.directive('header',function($rootScope){
		return {
        templateUrl:'scripts/directives/header/header.html',
        restrict: 'E',
        replace: true,
    	}
	});



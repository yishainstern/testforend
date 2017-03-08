'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp').factory('service', ['$rootScope', '$http', '$q', '$state', '$timeout', function ($rootScope, $http, $q, $state, $timeout) {
	return {
		ajaxfunc: function(task){
			var deferred = $q.defer();
			var form = document.forms.namedItem("form");
			var data_to_send = new FormData(form);
			data.append('task',task);
			var httpDetails = {
				url: $rootScope.server_domain,
				method: theMethod,
				data: data_to_send,
				contentType: "application/json"	
			};
			$http(httpDetails).
           	success(function (json) {deferred.resolve(json);}).
           	error(function (err) {deferred.reject(err);});
            return deferred.promise;
		},
		check: function(){
			alert('sadsa');
		}
	}
}]);
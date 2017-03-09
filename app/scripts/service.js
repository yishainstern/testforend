'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp').factory('service', ['$rootScope', '$http', '$q', '$state', '$timeout', function ($rootScope, $http, $q, $state, $timeout) {
	return {
/*		ajaxfunc: function(task,form_naame){
			var deferred = $q.defer();
			var form = document.forms.namedItem(form_naame);
			var data_to_send = new FormData(form);
			data_to_send.append('task',task);
			var httpDetails = {
				url: $rootScope.server_domain,
				method: 'POST',
				data: data_to_send,
        		headers : {'Content-Type': 'application/x-www-form-urlencoded'},
        		transformRequest: angular.identity
			};
			$http(httpDetails).
           	success(function (json) {deferred.resolve(json);}).
           	error(function (err) {deferred.reject(err);});
            return deferred.promise;
		},*/
		ajaxfunc: function(task,form_name,form_data){
			var deferred = $q.defer();
			var form;
			if (!form_data){
				form = document.forms.namedItem(form_name);
			}else {
				form = form_data;
			}
			var data_to_send = new FormData(form);
			data_to_send.append('task',task);
			$.ajax({
        		url: $rootScope.server_domain,
        		type: "POST",
        		data: data_to_send,
        		processData: false,
        		contentType: false,
        		error: function(data){
        			deferred.reject(data);
        		},
        		success:  function(data){
        			deferred.resolve(data);
        		} 
    		});
            return deferred.promise;
		},		
		check: function(){
			alert('sadsa');
		}
	}
}]);
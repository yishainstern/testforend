'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp').factory('service', ['$rootScope', '$http', '$q', '$state', '$timeout', function ($rootScope, $http, $q, $state, $timeout) {
	return {
		ajaxfunc: function(task,form_name,form_data){
			var deferred = $q.defer();
			var form;
			var data_to_send;
			if (!form_data){//did you send a formData or not
				form = document.forms.namedItem(form_name);
				data_to_send = new FormData(form);
			}else {
				form = form_data;
				data_to_send = form;
			}
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
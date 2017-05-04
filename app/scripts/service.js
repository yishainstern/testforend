'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp').factory('service', ['$interval', '$rootScope', '$http', '$q', '$state', '$timeout','$stateParams', function ($interval, $rootScope, $http, $q, $state, $timeout, $stateParams) {
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
        filefunc: function(task,form_name,form_data){
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
                success:  function(data, status, headers, config){
                    var anchor = angular.element('<a/>');
                    anchor.attr({
                        href: 'data:attachment/csv;charset=utf-8,' + encodeURI(data),
                        target: '_blank',
                        download: 'filename.csv'
                    })[0].click();
                    deferred.resolve(data);
                } 
            });
            return deferred.promise;
        },        
		intervalfunc:function(delegate){
			var stop = $interval(function() {
				if ($rootScope.user.details.first_name && $rootScope.user.details.password && $stateParams.id){
					//stop interval
					$interval.cancel(stop);
	        		stop = undefined;
					var form_data = new FormData();
    				form_data.append('userName',$rootScope.user.details.userName);
    				form_data.append('password',$rootScope.user.details.password);
    				form_data.append('id',$stateParams.id);
    				delegate.ajaxfunc('get_project_progress','',form_data)
    				.then(function(data){
        				data = JSON.parse(data);
        				if (data && data.project && data.project.details && data.project.details.progress){
        					$rootScope.project = data.project; 
        					$rootScope.project_arr = [];
        					var flag = $rootScope.project.details.progress.start;
        					while(flag){
        						$rootScope.project_arr.push($rootScope.project.details.progress.mille_stones[flag]);
        						flag = $rootScope.project.details.progress.mille_stones[flag].next;
        					}
        					$rootScope.$broadcast('project_object_exsites');
        					console.log($rootScope.project);
        				}
    				},function(data){console.log(data);});    	
					}else{/*keep going*/}
			},300);
		},
		check: function(){
			alert('sadsa');
		}
	}
}]);
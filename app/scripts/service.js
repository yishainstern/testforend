'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp').factory('service', ['$interval', '$rootScope', '$http', '$q', '$state', '$timeout','$stateParams', function ($interval, $rootScope, $http, $q, $state, $timeout, $stateParams) {
		return {
        //main function for http post request's. can get a form data or get a form id and send it to ther server_domain var
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
        		success: function(data){
                    data = $rootScope.checkJson(data);
                    if (data && data.status && data.status==555){
                       $state.transitionTo('enter');
                    }
        			deferred.resolve(data);
        		} 
    		});
            return deferred.promise;
		},
        //after getting a output of the learning task, from here you download a file from the output to your pc.
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
                success:  function(data, status, headers, xhr){
                    var anchor = angular.element('<a/>');
                    anchor.attr({
                        href: 'data:attachment/csv;charset=utf-8,' + encodeURI(data),
                        target: '_blank',
                        download: headers.getResponseHeader("thename")
                    })[0].click();
                    deferred.resolve(data);
                } 
            });
            return deferred.promise;
        },     
        //When loading a controller or opening a direct url (the javascripts objects do not exist). get the details from the server to know which user and witch project we are standing on.   
		intervalfunc:function(delegate){
			var stop = $interval(function() {
				if ($rootScope.user.first_name && $stateParams.id){
					//stop interval
					$interval.cancel(stop);
	        		stop = undefined;
					var form_data = new FormData();
    				form_data.append('userName',$rootScope.user.userName);
    				form_data.append('id',$stateParams.id);
    				delegate.ajaxfunc('get_project_progress','',form_data)
    				.then(function(data){
                        if (data && data.status && data.status==555){
                            $state.transitionTo('enter');
                        }
        				if (data && data.project.progress){
        					$rootScope.project = data.project; 
        					$rootScope.project_arr = [];
        					var flag = $rootScope.project.progress.start;
        					while(flag){
        						$rootScope.project_arr.push($rootScope.project.progress.mille_stones[flag]);
        						flag = $rootScope.project.progress.mille_stones[flag].next;
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
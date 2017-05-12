'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp').factory('config', ['$rootScope', '$state', '$timeout', 'service', '$stateParams', function ($rootScope, $state, $timeout, service, $stateParams) {
	//yishai computer
	$rootScope.server_domain = 'http://local.test/testforend/php/index.php';
	//windows server
    //$rootScope.server_domain = 'http://local.test/php/index.php';
	//user obj
	$rootScope.user = {
		details: {},
		list:[]
	}

	$rootScope.project = {
        details: {}
    };

/*	//project page 
	$rootScope.project_page = function(){
		if ($rootScope.project.details.name){
			$state.transitionTo('dashboard.project',{id:$rootScope.project.details.name});	
		}
	}
*/
    $rootScope.checkJson = function(str){
        try{
            var obj = JSON.parse(str);
            return obj;
        }catch(err){
            alert('server crash try agin');
            return null;
        }
    }

    $rootScope.call_user = function(){
        var form_data = new FormData();
        if (!$rootScope.user.details.userName || !$rootScope.user.details.password){
            $rootScope.user.details.userName = localStorage.getItem('name');
            $rootScope.user.details.password = localStorage.getItem('password');
        }
        if ($rootScope.user.details.userName && $rootScope.user.details.password){
            form_data.append('userName',$rootScope.user.details.userName);
            form_data.append('password',$rootScope.user.details.password);
            service.ajaxfunc('get_user_list','',form_data)
            .then(function(data){
                data = JSON.parse(data);
                if (data && data.status && data.status==111){
                    $rootScope.user = data.user;
                    console.log($rootScope.user);
                }
            },function(data){
                console.log(data);
            });        
        }else{
            $state.transitionTo('enter');
        }
    }
    if($state.current.name!='enter'){
        $rootScope.call_user();
    }
}]);
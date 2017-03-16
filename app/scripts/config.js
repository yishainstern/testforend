'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp').factory('config', ['$rootScope', '$state', '$timeout', 'service', function ($rootScope, $state, $timeout, service) {
	//yishai computer
	$rootScope.server_domain = 'http://local.test/index.php';
	//windows server

	//disciption of array 
	$rootScope.level_discirption = {
		create_folders:'create folders in server file system for your project',
		clone:'clone your project from git to our server',
		start_offline:'use your code as a data base to do a offline learning of the code',
		end_offline:'code studing is done',
		start_testing:'run maven in the server to test you code and get failers of the code with maven and surfire',
		end_testing:'maven task was done and we have some file with discription of failers',
		get_prediction:'get a prediction of your code'
	}

	//user obj
	$rootScope.user = {
		details: {},
		list:[]
	}

	$rootScope.project = {};


    var form_data = new FormData();
    if (!$rootScope.user.details.name || !$rootScope.user.details.password){
        $rootScope.user.details.name = localStorage.getItem('name');
        $rootScope.user.details.password = localStorage.getItem('password');
    }    
    form_data.append('userName',$rootScope.user.details.name);
    form_data.append('password',$rootScope.user.details.password);
    service.ajaxfunc('get_user_list','',form_data)
    .then(function(data){
        data = JSON.parse(data);
        if (data && data.status && data.status==111){
            $rootScope.user = data.user;
        }
    },function(data){
        console.log(data);
    });

}]);
'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp').factory('config', ['$rootScope', '$state', '$timeout', function ($rootScope, $state, $timeout) {
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
}]);
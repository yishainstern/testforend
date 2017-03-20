'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp').factory('config', ['$rootScope', '$state', '$timeout', 'service', '$stateParams', function ($rootScope, $state, $timeout, service, $stateParams) {
	//yishai computer
	$rootScope.server_domain = 'http://local.test/index.php';
	//windows server

	//disciption of array 
	$rootScope.level_discirption = {
		create_folders:{text:'create folders in our server file system were we will store your project',title:'create folders'},
		start_clone:{text:'clone your project from git to our server file system',title:'start clone'},
		end_clone:{text:'checking if the clone task is done',title:'end clone'},
		start_offline:{text:'use your code as a data-base to do a offline learning of the code',title:'start offline'},
		end_offline:{text:'code studing in offline is done',title:'end offline'},
		pick_version:{text:'pick a version for runing some testes on your code',title:'pick version'},
		prepare_jar:{text:'notify us to prepare a jar to run some tests on your code',title:'prepare jar'},
		prepare_mvn:{text:'notify us to get ready all files to run mavn on your ocmputer',title:'prepare mvn'},
		start_testing:{text:'run maven in the server to test you code and get failers of the code with maven and surfire',title:'start testing'},
		end_testing:{text:'maven task was done and we have some file with discription of failers',title:'end testing'},
		get_prediction:{text:'get a prediction of your code',title:'get prediction'},
		upload_bug_files:{text:'upload a csv file from bugzila of known bugs and tell us the versions you want us to check',title:'upload bug files'}
	}

	//user obj
	$rootScope.user = {
		details: {},
		list:[]
	}

	$rootScope.project = {};

	//project page 
	$rootScope.project_page = function(){
		if ($rootScope.project.details.name){
			$state.transitionTo('dashboard.project',{id:$rootScope.project.details.name});	
		}
	}

    var form_data = new FormData();
    if (!$rootScope.user.details.userName || !$rootScope.user.details.password){
        $rootScope.user.details.userName = localStorage.getItem('name');
        $rootScope.user.details.password = localStorage.getItem('password');
    }    
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

}]);
'use strict';

/**
 * @ngdoc directive
 * @name izzyposWebApp.directive:adminPosHeader
 * @description
 * # adminPosHeader
 */
angular.module('sbAdminApp').factory('config', ['$rootScope', '$state', '$timeout', 'service', '$stateParams', function ($rootScope, $state, $timeout, service, $stateParams) {
	   //server_domain is the domain of the server side
       //yishai computer
	   $rootScope.server_domain = 'http://local.test/testforend/php/index.php';
       //yishai wrock
       //$rootScope.server_domain = 'http://local.test/php/index.php';
	   //windows server
        //$rootScope.server_domain = 'http://132.72.64.18/testforend/php/index.php';
        //windows server
        $rootScope.server_domain = 'http://debguer.ise.bgu.ac.il/secure/in/testforend/app/php/index.php';
	//user object, all of users details
	$rootScope.user = {
		list:[]
	}
    //project object, all of projects details
	$rootScope.project = {
    };

    //clean all objects and logs out to login page.
    $rootScope.log_out = function(){
        $rootScope.user = {
            list:[]
        }
        $rootScope.project = {
        };  
        localStorage.setItem('name',"");
        $state.transitionTo('enter');    
    }
    //after getting a json from server, check if the json is valid or alert if there was a crash in the server
    $rootScope.checkJson = function(str){
        try{
            var obj = JSON.parse(str);
            return obj;
        }catch(err){
            alert('server crash try agin');
            return null;
        }
    }
    //check the localStorage who was looed in last.
    $rootScope.call_user = function(){
        var form_data = new FormData();
        if (!$rootScope.user.userName){
            $rootScope.user.userName = localStorage.getItem('name');
        }
        if ($rootScope.user.userName){
            form_data.append('userName',$rootScope.user.userName);
            service.ajaxfunc('get_user_list','',form_data)
            .then(function(data){
                if (data && data.status && data.status==111){
                    $rootScope.user = data.user;
                    $rootScope.$broadcast('user_in');
                    console.log($rootScope.user);
                }
                if (data && data.status && data.status==555){
                    $state.transitionTo('enter');
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
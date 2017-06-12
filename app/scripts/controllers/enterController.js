'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('enterController', ['$scope', '$timeout', '$rootScope','service','config', '$state', function ($scope, $timeout, $rootScope, service,config,$state) {
    $scope.login_details = {};
    $scope.sginup_details = {};
    $scope.rong_password = false;
    $scope.sign_up_error = '';
    $scope.succsess = '';
    $scope.sign_text = '';
    $scope.error_sign = false;
    $scope.error_login = false;
    $scope.display_sgin_text = 'משתמש כבר קיים';
    $scope.display_login_text = 'משתמש כבר קיים';
    $scope.show_loader = false;
    $scope.log_show = true;
    $scope.valid_email = function(email){
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    
    $scope.show_error = function(str,flag){
        $scope.display_sgin_text = str;
        $scope.display_login_text = str; 
        $scope[flag] = true;
         $scope.show_loader = false;
    }

    $scope.success_sgin = function(data){
        data = JSON.parse(data);
        console.log(data);
        if (data && data.status && data.status==111){
            $rootScope.user = data.user; 
            localStorage.setItem('name',$rootScope.user.details.userName);
            localStorage.setItem('password',$rootScope.user.details.password);
            $state.transitionTo('dashboard.listUser',{user:$rootScope.user.details.userName});
        }else if (data && data.status && data.status==1){
            $scope.show_error('user alredy exsists','error_sign');
        }
    }

    $scope.fail_sgin = function(data){
        $scope.show_error('some failer..try agin','error_sign');        
        console.log(data);
    }

    $scope.success_log_in = function(data){
        data = JSON.parse(data);
        console.log(data);
        if (data && data.status && data.status==111){
            $rootScope.user = data.user; 
            localStorage.setItem('name',$rootScope.user.details.userName);
            localStorage.setItem('password',$rootScope.user.details.password);
            $state.transitionTo('dashboard.listUser',{user:$rootScope.user.details.userName});
        }else if (data && data.status && data.status==1){
            $scope.show_error('rong user name or password','error_login');
        }else if (data && data.status && data.status==2){
            $scope.show_error('rong user name...user name does not exsit','error_login');
        }        
    }

    $scope.fail_log_in = function(data){
        $scope.show_error('some failer..try agin','error_login'); 
    }

    $scope.sgin_task = function(form_naame){
        $scope.show_loader = true;
        if (!$scope.sginup_details.userName || !$scope.sginup_details.password ||!$scope.sginup_details.user_email ){
            $scope.show_error('all fileds are required','error_sign');        
            return;
        }
        if (!$scope.valid_email($scope.sginup_details.user_email)){
            $scope.show_error('email not valid','error_sign');        
            return;
        }
       
        $scope.display_sgin_text = '';        
        $scope.error_sign = false;
        service.ajaxfunc('sgin_up',form_naame,false)
        .then(function(data){$scope.success_sgin(data);},
            function(data){$scope.fail_sgin(data);});
    }

    $scope.login_task = function(){
         $scope.show_loader = true;
        if (!$scope.login_details.userName || !$scope.login_details.password){
            $scope.show_error('both fileds are required','error_login');        
            return;
        }
        $scope.display_sgin_text = '';        
        $scope.error_login = false;
        service.ajaxfunc('log_in','login_form',false)
        .then(function(data){$scope.success_log_in(data);},
            function(data){$scope.fail_log_in(data);});
    }

;}]);
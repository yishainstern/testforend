'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('listAdminUserController', ['$scope', '$timeout', '$rootScope','service','config','$state','$stateParams', function ($scope, $timeout, $rootScope, service,config,$state,$stateParams) {
    $scope.users_data= [];
    $scope.display_create_users_data_table_error_text = '';
    $scope.show_loader = false;
    $scope.show_mask ={
        flag:false
    }
    //Show test of error or success.
    $scope.show_error = function(str,flag){
        $scope.display_create_users_data_table_error_text =str;
        $scope[flag] = true;
        $scope.show_loader = false;
    }
    //change state to make a new project
    $scope.go_to_users_data = function(){
        $state.transitionTo('dashboard.usersDataTables',{user:$rootScope.user.userName});
    }
    //create the users data table
    $scope.get_users_data_table = function(){
        service.ajaxfunc('get_users_data','',false)
        .then(function(data){$scope.success_create_users_data(data);},
            function(data){$scope.fail_create_users_data(data);});
    }
    
    $scope.fail_create_users_data = function(data){
        $scope.show_error('Failes to retrieve users data','create_users_data_table_error'); 
    }
    //builds the table from the data
    $scope.success_create_users_data = function(data){
        $scope.users_data =data.arr;
       /*  var obj = $scope.users_data.arr;
        var tbl=$("<table/>").attr("id","mytable");
        $("#div1").append(tbl);
        for(var i=0;i<obj.length;i++)
        {
            var tr="<tr>";
            var td1="<td>"+obj[i]["userName"]+"</td>";
            var td2="<td>"+obj[i]["first_name"]+"</td>";
            var td3="<td>"+obj[i]["last_name"]+"</td></tr>";
            
        $("#mytable").append(tr+td1+td2+td3);
        }  */
    }
;}]);
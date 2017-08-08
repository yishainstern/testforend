'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('projectController', ['$scope', '$timeout', '$rootScope', '$interval', 'service','config','flow','$state', '$stateParams', function ($scope, $timeout, $rootScope, $interval, service, config, flow,$state, $stateParams) {
 	service.intervalfunc(service);
 	$scope.show_loader = true;
 	$scope.how_much_done = 10;
 	$scope.open_details = false;
 	var p_interval;
 	//Array of project progress.
 	$scope.userArray = [
 		{
 			task:'Sign & Clone',
 			title:'Clone your project.',
 			description: 'This part tells you if we uploaded your git project to our server, follow the instructions.',
 			flag:false,
 			status:"",
 			has_page:false
 		},
 		{
 			task:'Details',
 			title:'Project details',
 			state: 'dashboard.all_details',
 			send:{task:'all_details'},
 			description:'Press here to get to the page where you give us all details we need for your project. After that, we will run an AI algorithm to learn about your code, follow the instructions.',
 			flag:false,
 			status:"Can not enter yet!",
 			has_page:false
 		},
		{
 			task:'Prediction & Diagnosis',
 			title:'Get prediction',
 			state:'dashboard.results',
 			description:'Get the output of our AI learning on your code, this part can take a lot of time (4-12 houres), please be patient.' ,
 			send:{task:'get_results'},
 			flag:false,
 			status:"No results yet",
 			has_page:false
 		} 		
 	];
 	//Event after project was loded from server.
 	$scope.$on('project_object_exsites',function(){
 		$scope.show_loader = false;
 		$scope.tmp = $rootScope.project.progress.mille_stones;
 		if (!$scope.tmp.end_clone.flag){
 			$scope.userArray[0].status= 'Still cloning';
 			$scope.userArray[0].flag= false;
 		}
 		if ($scope.tmp.end_clone.flag) {
 			$scope.userArray[0].status= 'Finished cloning, go to next task.';
 			$scope.userArray[0].flag= true;
 			$scope.userArray[1].status= 'Enter this page.';
 			$scope.userArray[1].has_page= true;
 			$scope.how_much_done = 30;
 		}
 		if ($scope.tmp.end_clone.flag && !$scope.tmp.start_offline.flag){
 			$scope.userArray[1].status= 'Enter this page. Did not start learning.';
 			$scope.userArray[1].flag= false;
 		}
 		if ($scope.tmp.end_clone.flag && $scope.tmp.start_offline.flag) {
 			$scope.userArray[1].status= 'Started learning';
 			$scope.userArray[1].flag= true;
 			$scope.userArray[1].has_page= false;
 		}
 		if ($scope.tmp.end_clone.flag && $scope.tmp.start_offline.flag && $scope.tmp.start_testing.flag){
 			$scope.how_much_done = 70;
 			$scope.userArray[1].status= 'Started maven testing.';
 			$scope.userArray[2].status= 'Has some details.';
 			$scope.userArray[2].has_page= true;
 		}
 		if ($scope.tmp.end_clone.flag && $scope.tmp.start_offline.flag && $scope.tmp.start_testing.flag && $scope.tmp.get_prediction.flag){
 			$scope.how_much_done = 100;
 			$scope.userArray[2].status= 'Has all details';
 			$scope.userArray[2].flag= true;
 		}
 		if ($rootScope.project.problem){
 			alert("Aborted testing because of some failure, please remove project!");
 		}
 		$timeout(function() {service.intervalfunc(service);}, 30000);
 	});
 	//Go to the page of the task that is described.
 	$scope.go_page = function(item){
 		if (item.send){
 			item.send.id = $rootScope.project.folderName;
 			item.send.user = $rootScope.user.userName;
 		}
 		$state.transitionTo(item.state,item.send);
 	}
;}]);
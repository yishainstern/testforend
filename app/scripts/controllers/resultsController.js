'use strict';
/**
 * @ngdoc function
 * @name sbAdminApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the sbAdminApp
 */
angular.module('sbAdminApp').controller('resultsController', ['$scope', '$timeout', '$rootScope','service','config','$state', '$stateParams', '$interval', function ($scope, $timeout, $rootScope, service,config,$state, $stateParams, $interval) {
 	service.intervalfunc(service);
    $scope.witch_file_name;
    $scope.show_loader = true;
    $scope.load_count = 0;
    $scope.info_txt = {txt:""};
    $scope.a_counter = 0;
    var t_stop;
    var swiper;
    $scope.matric = {
        all:{
            files: [],
            methods:[]
        },
        most:{
            files: [],
            methods:[]
        }
    };
    $scope.add_to_list = function(arr,loop){
        for (var i=0;i<loop.length;i++){
            if (loop[i].file){
                arr[arr.length]=loop[i].file;
            }
        }
        return arr;
    }
    var p_stop = $interval(function() {
        if (typeof Swiper == 'function' && $('.swiper-container').length > 0 && $('.swiper-pagination').length > 0 && $('.swiper-slide').length > 0){
            //stop interval
            $interval.cancel(p_stop);
            p_stop = undefined;
            swiper = new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                paginationClickable: true,
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev',
                spaceBetween: 30
            });            
        }else {
            //keep it alive
        }
    },300);
    $scope.files = [];
    //Slide between sections of results, prediction and diagnoses.
    $scope.swipe_res = function(index){
        if (typeof Swiper == 'function'){
            swiper.slideTo(index);
        }
    }
    //display the file
    $scope.display_file = function(item,folder){
         $scope.show_loader = true;
        var form = document.forms.namedItem('results');
        var data_to_send = new FormData(form);
        data_to_send.append('witch_file',item);
        data_to_send.append('witch_folder',folder);
        service.ajaxfunc('display_file','results',data_to_send)
        .then(function(data){
            $scope.show_loader = false;
            $("#openchartmodat").click();
            $rootScope.$broadcast("chartdb",{data:data});
        },function(data){alert('bad'); $scope.show_loader = false;}
            );  
    }
    //display the file
    $scope.zip_files = function(folder){
         $scope.show_loader = true;
        var form = document.forms.namedItem('results');
        var data_to_send = new FormData(form);
        var files_to_send = [];
        files_to_send = $scope.add_to_list(files_to_send,$scope.matric.all.files);
        files_to_send = $scope.add_to_list(files_to_send,$scope.matric.all.methods);
        files_to_send = $scope.add_to_list(files_to_send,$scope.matric.most.files);
        files_to_send = $scope.add_to_list(files_to_send,$scope.matric.most.methods);
        data_to_send.append('witch_files',files_to_send);
        data_to_send.append('witch_folder',folder);
        data_to_send.append('task','zip_files');
        var zip = new JSZip();
        zip.file("Hello.txt", "Hello World\n");
        zip.generateAsync({type:"blob"})
        .then(function(content) {
            // see FileSaver.js
            saveAs(content, "example.zip");
        });
    }
    //Get a from the server.
    $scope.get_file = function(item,folder){
         $scope.show_loader = true;
        var form = document.forms.namedItem('results');
        var data_to_send = new FormData(form);
        data_to_send.append('witch_file',item);
        data_to_send.append('witch_folder',folder);
        service.filefunc('get_file','results',data_to_send)
        .then(function(data){
                 $scope.show_loader = false;
            },function(data){alert('bad'); $scope.show_loader = false;}
            );  
    }
    //Event after project was loded from server. get all list of files we need.
    $scope.$on('project_object_exsites',function(){
        t_stop = $interval(function() {
        if ($rootScope.user.userName && $rootScope.project.folderName && $rootScope.project.gitName){
            //stop interval
            $interval.cancel(t_stop);
            t_stop = undefined;
            service.ajaxfunc('get_output','results',false)
            .then(function(data){
                $scope.load_count ++;
                if ($scope.load_count==2){
                    $scope.show_loader = false;
                }
                if (data.status == "111"){
                    $scope.files = data.files;
                }
            },function(data){alert('bad')});
            service.ajaxfunc('get_experiments','results',false)
            .then(function(data){
                $scope.load_count ++;
                if ($scope.load_count==2){
                    $scope.show_loader = false;
                }
                if (data.status == 111){
                    $scope.experiments = data.files;
                    $scope.matric = {
                        all:{
                            files: [],
                            methods:[]
                        },
                        most:{
                            files: [],
                            methods:[]
                        }
                    };
                    for (var ext =0;ext<$scope.experiments.length;ext++){
                        var name = ($scope.experiments[ext]).dir_name;
                        var tmpArr = ($scope.experiments[ext]).dir_arr;
                        for (var inext =0;inext<tmpArr.length;inext++){
                            var t_p = "";
                            var t_i_p = "";
                            if (name.includes("All") || name.includes("all")){
                                t_p = "all";
                            }else if (name.includes("Most") || name.includes("most")){
                                t_p = "most";
                            }
                            if (name.includes("File") || name.includes("file")){
                                t_i_p = "files";
                            }else if (name.includes("Method") || name.includes("method")){
                                t_i_p = "methods";
                            }
                            if (($scope.matric[t_p]) && (($scope.matric[t_p])[t_i_p]) ){
                                var t_len = (($scope.matric[t_p])[t_i_p]).length;
                                (($scope.matric[t_p])[t_i_p])[t_len] = {folder:name,file:tmpArr[inext]};
                            }
                        }
                    }
                    console.log($scope.matric);
                }
            },function(data){alert('bad')}); 
        }else {
            //keep it alive
        }
    },300);
        
    });
    //Get information in files, is not ready until we will have all the information we need.
    $scope.get_file_info = function(flag,item,index){
        $scope.show_loader = true;
        $scope.witch_file_name = item;
        var form = document.forms.namedItem('results');
        var data_to_send = new FormData(form);
        data_to_send.append('file_name_output',item);
        data_to_send.append('which_output',flag);
        service.ajaxfunc('get_file_info','results',data_to_send)
            .then(function(data){
                data = $rootScope.checkJson(data);
                $scope.show_loader = false;
                if ((data.status==1||data.status==2)&& data.info){
                     $scope.info_txt.txt = data.info;
                }
                $($('.togglt_trigger')[index]).click();        
            },function(data){
                alert('try agin');
                $scope.show_loader = false;
            }); 
    }
    //Get information in files, is not ready until we will have all the information we need.
    $scope.get_file_diagnosis = function(flag,item,parent, index){
        $scope.show_loader = true;
        $scope.witch_file_name = item;
        var form = document.forms.namedItem('results');
        var data_to_send = new FormData(form);
        data_to_send.append('file_name_output',item);
        data_to_send.append('which_output',flag);
        service.ajaxfunc('get_file_info','results',data_to_send)
            .then(function(data){
                data = $rootScope.checkJson(data);
                $scope.show_loader = false;
                if ((data.status==1||data.status==2)&& data.info){
                     $scope.info_txt.txt = data.info;
                }
                $($('.togglt_trigger')[index]).click();        
            },function(data){
                alert('try agin');
                $scope.show_loader = false;
            }); 
    }
}]);
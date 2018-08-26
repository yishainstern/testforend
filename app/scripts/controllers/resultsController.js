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
    $scope.watch_is_visible = false;
    $scope.prediction_is_visible =true;
    $scope.diagnosis_is_visible = false;
    $scope.failed_tests_is_visible = false;
    $scope.failed_tests_file_name = "outcomes";
    $scope.show_all_tests = true;
    $scope.show_only_faulty_tests = false;
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
    /* $scope.fault_colors =[
        '#8cff66',
        '#b3ff66',
        '#d9ff66',
        '#ffff66',
        '#ffd966',
        '#ffb366',
        '#ff8c66',
        '#ff6666'
    ] */
    $scope.fault_colors =[
        '#99ff99',
        '#b3ff99',
        '#e6ff99',
        '#ffff99',
        '#ffe699',
        '#ffcc99',
        '#ffb399',
        '#ff9999'
    ]
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
        if(index == 0){
            $scope.show_prediction();
        }else{
            $scope.show_diagnosis();
        }
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
        service.ajaxfunc('zip_files','results',data_to_send)
        .then(function(data){
            var zip = new JSZip();
            if (data.status==111 && data.files ){
                var keys = Object.keys(data.files);
                for (var i = 0; i < keys.length; i++) {
                    zip.file(keys[i], data.files[keys[i]]);
                }
                zip.generateAsync({type:"blob"})
                .then(function(content) {
                    // see FileSaver.js
                    saveAs(content, "example.zip");
                });
            }
            $scope.show_loader = false;
        },function(data){alert('bad'); $scope.show_loader = false;});  
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
    //Watch the item
    $scope.watch_file = function(item,folder){
        $scope.show_loader = true;
        var form = document.forms.namedItem('results');
        var data_to_send = new FormData(form);
        data_to_send.append('witch_file',item);
        data_to_send.append('witch_folder',folder);
        item = item.replace(".csv","");
        item = item.replace(".json","");
        data_to_send.append('watch_file_name',item);
        service.ajaxfunc('get_watch','results',data_to_send)
        .then(function(data){
            $scope.data = data.watch_obj;
            $scope.show_loader = false;
            $scope.watch_is_visible = true;
        },function(data){alert('bad')});
        
    }
    
    //Watch the item
    $scope.back_to_results = function(){
        $scope.watch_is_visible = false;
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
    $scope.prediction_show = function(item){
        return item.endsWith(".csv") && !item.includes("diagnosis");
    }
    $scope.diagnosis_show = function(item){
        return (
        item == "diagnosis_files_all.json"
        ||item == "diagnosis_files_most.json"
        ||item == "diagnosis_methods_all.json"
        ||item == "diagnosis_methods_most.json"
        );
    }
    //Returns true if the prediction watch is visible
    $scope.check_prediction_is_visible = function(){
        return (
        $scope.prediction_is_visible
        &&!$scope.diagnosis_is_visible
        &&!$scope.failed_tests_is_visible
        );
    }
    //Returns true if the diagnosis watch is visible
    $scope.check_diagnosis_is_visible = function(){
        return (
        !$scope.prediction_is_visible
        &&$scope.diagnosis_is_visible
        &&!$scope.failed_tests_is_visible
        );
    }
    //Returns true if the failed_tests watch is visible
    $scope.check_failed_tests_is_visible = function(){
        return (
        !$scope.prediction_is_visible
        &&!$scope.diagnosis_is_visible
        &&$scope.failed_tests_is_visible
        );
    }
    //Set the prediction watch visible
    $scope.show_prediction = function(){
        $scope.prediction_is_visible =true;
        $scope.diagnosis_is_visible =false;
        $scope.failed_tests_is_visible =false;
    }
    //Set the diagnosis watch visible
    $scope.show_diagnosis = function(){
        $scope.prediction_is_visible =false;
        $scope.diagnosis_is_visible =true;
        $scope.failed_tests_is_visible =false;
    }
    //Set the failed tests watch visible
    $scope.show_failed_tests = function(){
        $scope.prediction_is_visible =false;
        $scope.diagnosis_is_visible =false;
        $scope.failed_tests_is_visible =true;
        $scope.watch_file($scope.failed_tests_file_name);
    }
    $scope.remove = function (scope) {
        scope.remove();
      };

      $scope.toggle = function (scope) {
        scope.toggle();
      };

      $scope.moveLastToTheBeginning = function () {
        var a = $scope.data.pop();
        $scope.data.splice(0, 0, a);
      };

      $scope.newSubItem = function (scope) {
        var nodeData = scope.$modelValue;
        nodeData._sub_packages.push({
          title: nodeData.title + '.' + (nodeData._sub_packages.length + 1),
          _sub_packages: []
        });
      };

      $scope.collapseAll = function () {
        $scope.$broadcast('angular-ui-tree:collapse-all');
      };

      $scope.expandAll = function () {
        $scope.$broadcast('angular-ui-tree:expand-all');
      };
      
      $scope.style_node = function(node){
        if(node.hasOwnProperty('_probability')){
            var i = Math.floor(node._probability*($scope.fault_colors.length-1));
            return {
                "background-color" : $scope.fault_colors[i]
            };
        }else if(node.hasOwnProperty('_outcome')){
            if(node._outcome == 'pass'){
                return {
                    "background-color" : $scope.fault_colors[0]
                };
            }else if(node._outcome == 'error'){
                return {
                    "background-color" : $scope.fault_colors[$scope.fault_colors.length/2]
                };
            }else if(node._outcome == 'failure'){
                return {
                    "background-color" : $scope.fault_colors[$scope.fault_colors.length-1]
                };
            }
        }
          
      };
      //Filters the list of the tests with respect to the user will
      $scope.failed_tests_filter = function(node){
        if($scope.show_all_tests){
            return true;
        }
        if($scope.show_only_faulty_tests){
            return node._outcome == 'failure' || node._outcome == 'error';
        }
      };
      //Sets all the tests in the tests watch will to be visible
      $scope.all_tests = function(node){
        $scope.show_all_tests = true;
        $scope.show_only_faulty_tests = false;
      };
      //Sets only the faulty tests in the tests watch will to be visible
      $scope.faulty_tests = function(node){
        $scope.show_all_tests = false;
        $scope.show_only_faulty_tests = true;
      };
      /* $scope.data =[
        {
            "_diagnosis": [
              {
                "_name": "org.apache.myfaces.view.facelets.statemanagerwithfaceletsclientsidetest"
              },
              {
                "_name": "javax.faces.application.facesmessagetest"
              },
              {
                "_name": "org.apache.myfaces.application.statemanagerimpltest"
              },
              {
                "_name": "org.apache.myfaces.view.facelets.el.tagvalueexpression"
              },
              {
                "_name": "org.apache.myfaces.view.facelets.tag.composite.serializableattributestestcase"
              },
              {
                "_name": "org.apache.myfaces.view.facelets.tag.jsf.html.htmltestcase"
              },
              {
                "_name": "org.apache.myfaces.view.facelets.faceletmultiplerequeststestcase"
              }
            ],
            "_probability": 1,
            "_name": 0
          },
          {
            "_diagnosis": [
              {
                "_name": "org.apache.myfaces.view.facelets.statemanagerwithfaceletsclientsidetest"
              },
              {
                "_name": "org.apache.myfaces.application.statemanagerimpltest"
              },
              {
                "_name": "javax.faces.application.facesmessage"
              },
              {
                "_name": "org.apache.myfaces.view.facelets.el.tagvalueexpression"
              },
              {
                "_name": "org.apache.myfaces.view.facelets.tag.composite.serializableattributestestcase"
              },
              {
                "_name": "org.apache.myfaces.view.facelets.tag.jsf.html.htmltestcase"
              },
              {
                "_name": "org.apache.myfaces.view.facelets.faceletmultiplerequeststestcase"
              }
            ],
            "_probability": 1.0000000000000016e-24,
            "_name": 1
          }
    ]; */
      /* $scope.data = [
         {
          '_name': 0,
          '_sub_packages': [
          {
              '_name':"org.apache.myfaces.view.facelets.statemanagerwithfaceletsclientsidetest"
          },
          {
              '_name': "javax.faces.application.facesmessagetest"
          },
          {
              '_name':"org.apache.myfaces.application.statemanagerimpltest"
          },
          {
              '_name':"org.apache.myfaces.view.facelets.el.tagvalueexpression"
          },
          {
              '_name':"org.apache.myfaces.view.facelets.tag.composite.serializableattributestestcase"
          },
          {
              '_name':"org.apache.myfaces.view.facelets.statemanagerwithfaceletsclientsidetest"
          },
          {
              '_name':"org.apache.myfaces.view.facelets.tag.jsf.html.htmltestcase"
          },
          {
              '_name':"org.apache.myfaces.view.facelets.faceletmultiplerequeststestcase"
          }
          ],
          '_probability': 1
        },
        {
          '_name': 1,
          '_sub_packages': [
            {
              '_name':"org.apache.myfaces.view.facelets.statemanagerwithfaceletsclientsidetest"
          },
          {
              '_name': "javax.faces.application.facesmessagetest"
          },
          {
              '_name':"org.apache.myfaces.application.statemanagerimpltest"
          },
          {
              '_name':"org.apache.myfaces.view.facelets.el.tagvalueexpression"
          },
          {
              '_name':"org.apache.myfaces.view.facelets.tag.composite.serializableattributestestcase"
          },
          {
              '_name':"org.apache.myfaces.view.facelets.statemanagerwithfaceletsclientsidetest"
          },
          {
              '_name':"org.apache.myfaces.view.facelets.tag.jsf.html.htmltestcase"
          },
          {
              '_name':"org.apache.myfaces.view.facelets.faceletmultiplerequeststestcase"
          }
          ],
          '_probability': 1.0000000000000016e-24
        }
      ] */
      /* $scope.data = [{
        '_name': 'node1',
        '_probability': 0.3,
        '_sub_packages': [
        {
            '_name': 'node1.1'
        },
        {
            '_name': 'node1.2'
        }
        ]
      }]; */
      /* $scope.data = [{
        'id': 1,
        'title': 'node1',
        'nodes': [
          {
            'id': 11,
            'title': 'node1.1',
            'nodes': [
              {
                'id': 111,
                'title': 'node1.1.1',
                'nodes': []
              }
            ]
          },
          {
            'id': 12,
            'title': 'node1.2',
            'nodes': []
          }
        ]
      }, {
        'id': 2,
        'title': 'node2',
        'nodrop': true, // An arbitrary property to check in custom template for nodrop-enabled
        'nodes': [
          {
            'id': 21,
            'title': 'node2.1',
            'nodes': []
          },
          {
            'id': 22,
            'title': 'node2.2',
            'nodes': []
          }
        ]
      }, {
        'id': 3,
        'title': 'node3',
        'nodes': [
          {
            'id': 31,
            'title': 'node3.1',
            'nodes': []
          }
        ]
      }]; */
}]);
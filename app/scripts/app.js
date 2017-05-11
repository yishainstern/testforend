'use strict';
/**
 * @ngdoc overview
 * @name sbAdminApp
 * @description
 * # sbAdminApp
 *
 * Main module of the application.
 */
angular
  .module('sbAdminApp', [
    'oc.lazyLoad',
    'ui.router',
    'ui.bootstrap',
    'angular-loading-bar',
  ])
  .config(['$stateProvider','$urlRouterProvider','$ocLazyLoadProvider',function ($stateProvider,$urlRouterProvider,$ocLazyLoadProvider) {
    
    $ocLazyLoadProvider.config({
      debug:false,
      events:true,
    });

    $urlRouterProvider.otherwise('/enter');

    $stateProvider
      .state('dashboard', {
        url:'/dashboard',
        templateUrl: 'views/dashboard/main.html',
        resolve: {
            loadMyDirectives:function($ocLazyLoad){
                return $ocLazyLoad.load(
                {
                    name:'sbAdminApp',
                    files:[
                    'scripts/directives/header/header.js',
                    'scripts/directives/header/header-notification/header-notification.js',
                    'scripts/directives/sidebar/sidebar.js',
                    'scripts/directives/sidebar/sidebar-search/sidebar-search.js'
                    ]
                }),
                $ocLazyLoad.load(
                {
                   name:'toggle-switch',
                   files:["bower_components/angular-toggle-switch/angular-toggle-switch.min.js",
                          "bower_components/angular-toggle-switch/angular-toggle-switch.css"
                      ]
                }),
                $ocLazyLoad.load(
                {
                  name:'ngAnimate',
                  files:['bower_components/angular-animate/angular-animate.js']
                })
                $ocLazyLoad.load(
                {
                  name:'ngCookies',
                  files:['bower_components/angular-cookies/angular-cookies.js']
                })
                $ocLazyLoad.load(
                {
                  name:'ngResource',
                  files:['bower_components/angular-resource/angular-resource.js']
                })
                $ocLazyLoad.load(
                {
                  name:'ngSanitize',
                  files:['bower_components/angular-sanitize/angular-sanitize.js']
                })
                $ocLazyLoad.load(
                {
                  name:'ngTouch',
                  files:['bower_components/angular-touch/angular-touch.js']
                })
            }
        }
    })
    .state('dashboard.home',{
      url:'/home',
      controller: 'MainCtrl',
      templateUrl:'views/dashboard/home.html',
      resolve: {
        loadMyFiles:function($ocLazyLoad) {
          return $ocLazyLoad.load({
            name:'sbAdminApp',
            files:[
              'scripts/controllers/main.js',
              'scripts/directives/timeline/timeline.js',
              'scripts/directives/notifications/notifications.js',
              'scripts/directives/chat/chat.js',
              'scripts/directives/dashboard/stats/stats.js'
            ]
          })
        }
      }
    })
    .state('dashboard.form',{
      templateUrl:'views/form.html',
      url:'/form'
    })
    .state('dashboard.blank',{
      templateUrl:'views/pages/blank.html',
      url:'/blank'
    })
    .state('login',{
      templateUrl:'views/pages/login.html',
      url:'/login'
    })
    .state('enter',{
      templateUrl:'views/pages/enter.html',
      url:'/enter',
      controller:'enterController',
      resolve: {
        loadMyFile:function($ocLazyLoad) {
          return $ocLazyLoad.load({
              name:'sbAdminApp',
              files:['scripts/controllers/enterController.js','styles/enter.css']
          })
        }
      }
    })
    .state('dashboard.listUser',{
      templateUrl:'views/pages/listUser.html',
      url:'/listUser/:user',
      controller:'listUserController',
      resolve: {
        loadMyFile:function($ocLazyLoad) {
          return $ocLazyLoad.load({
              name:'sbAdminApp',
              files:['scripts/controllers/listUserController.js','styles/listUser.css']
          })
        }
      }
    })
    .state('dashboard.newProject',{
      templateUrl:'views/pages/new-project.html',
      url:'/newProject/:user',
      controller:'newProjectController',
      resolve: {
        loadMyFile:function($ocLazyLoad) {
          return $ocLazyLoad.load({
              name:'sbAdminApp',
              files:['scripts/controllers/newProjectController.js','styles/new-project.css']
          })
        }
      }
    })
    .state('dashboard.project',{
      templateUrl:'views/pages/project-page.html',
      url:'/project/:id',
      controller:'projectController',
      resolve: {
        loadMyFile:function($ocLazyLoad) {
          return $ocLazyLoad.load({
              name:'sbAdminApp',
              files:['scripts/controllers/projectController.js','styles/project.css','scripts/directives/timeline/timeline.js']
          })
        }
      }
    })
    .state('dashboard.prepareOffline',{
      templateUrl:'views/pages/prepare-offline.html',
      url:'/prepareOffline/:id/:task',
      controller:'prepareOfflineController',
      resolve: {
        loadMyFile:function($ocLazyLoad) {
          return $ocLazyLoad.load({
              name:'sbAdminApp',
              files:[
                'scripts/controllers/prepareOfflineController.js',
                'styles/prepareOffline.css',
                'scripts/directives/goToList/goToList.js'
              ]
          })
        }
      }
    })     
    .state('dashboard.prepareOnline',{
      templateUrl:'views/pages/prepare-online.html',
      url:'/prepareOnline/:id/:task',
      controller:'prepareOnlineController',
      resolve: {
        loadMyFile:function($ocLazyLoad) {
          return $ocLazyLoad.load({
              name:'sbAdminApp',
              files:[
                'scripts/controllers/prepareOnlineController.js',
                'styles/prepareOnline.css',
                'bower_components/swiper/dist/js/swiper.min.js',
                'bower_components/swiper/dist/css/swiper.min.css'
              ]
          })
        }
      }
    })
    .state('dashboard.all_details',{
      templateUrl:'views/pages/all_details.html',
      url:'/all_details/:id/:task',
      controller:'all_details',
      resolve: {
        loadMyFile:function($ocLazyLoad) {
          return $ocLazyLoad.load({
              name:'sbAdminApp',
              files:[
                'scripts/controllers/all_details.js',
                'styles/all_details.css',
                'bower_components/swiper/dist/js/swiper.min.js',
                'bower_components/swiper/dist/css/swiper.min.css',
                'scripts/directives/goToList/goToList.js'
              ]
          })
        }
      }
    })
    .state('dashboard.prediction',{
      templateUrl:'views/pages/prediction.html',
      url:'/prediction/:id/:task',
      controller:'predictionController',
      resolve: {
        loadMyFile:function($ocLazyLoad) {
          return $ocLazyLoad.load({
              name:'sbAdminApp',
              files:[
                'scripts/controllers/predictionController.js',
                'styles/prediction.css',
                'bower_components/swiper/dist/js/swiper.min.js',
                'bower_components/swiper/dist/css/swiper.min.css'
              ]
          })
        }
      }
    })    
    .state('dashboard.pomUpdate',{
      templateUrl:'views/pages/update-pom.html',
      url:'/pomUpdate/:id/:task',
      controller:'pomController',
      resolve: {
        loadMyFile:function($ocLazyLoad) {
          return $ocLazyLoad.load({
              name:'sbAdminApp',
              files:['scripts/controllers/pomController.js','styles/pom.css']
          })
        }
      }
    })              
    .state('dashboard.runOffline',{
      templateUrl:'views/pages/run-offline.html',
      url:'/runOffline/:id/:task',
      controller:'runOfflineController',
      resolve: {
        loadMyFile:function($ocLazyLoad) {
          return $ocLazyLoad.load({
              name:'sbAdminApp',
              files:[
                'scripts/controllers/runOfflineController.js',
                'styles/runOffline.css',
                'scripts/directives/goToList/goToList.js'
              ]
          })
        }
      }
    })            
    .state('dashboard.clone',{
      templateUrl:'views/pages/clone.html',
      url:'/clone/:id/:task',
      controller:'cloneController',
      resolve: {
        loadMyFile:function($ocLazyLoad) {
          return $ocLazyLoad.load({
              name:'sbAdminApp',
              files:[
                'scripts/controllers/cloneController.js',
                'styles/clone.css',
                'scripts/directives/goToList/goToList.js'
              ]
          })
        }
      }
    })
    .state('dashboard.prepareVersion',{
      templateUrl:'views/pages/prepareVersion.html',
      url:'/prepareVersion/:id/:task',
      controller:'prepareVersionController',
      resolve: {
        loadMyFile:function($ocLazyLoad) {
          return $ocLazyLoad.load({
              name:'sbAdminApp',
              files:[
                'bower_components/swiper/dist/js/swiper.min.js',
                'bower_components/swiper/dist/css/swiper.min.css',
                'scripts/controllers/prepareVersionController.js',
                'styles/prepareVersion.css',
                'scripts/directives/goToList/goToList.js'
              ]
          })
        }
      }
    })                      
    .state('dashboard.chart',{
      templateUrl:'views/chart.html',
      url:'/chart',
      controller:'ChartCtrl',
      resolve: {
        loadMyFile:function($ocLazyLoad) {
          return $ocLazyLoad.load({
            name:'chart.js',
            files:[
              'bower_components/angular-chart.js/dist/angular-chart.min.js',
              'bower_components/angular-chart.js/dist/angular-chart.css'
            ]
          }),
          $ocLazyLoad.load({
            name:'sbAdminApp',
            files:['scripts/controllers/chartContoller.js']
          })
        }
      }
    })
    .state('dashboard.table',{
      templateUrl:'views/table.html',
      url:'/table'
    })
    .state('dashboard.panels-wells',{
        templateUrl:'views/ui-elements/panels-wells.html',
        url:'/panels-wells'
    })
    .state('dashboard.buttons',{
      templateUrl:'views/ui-elements/buttons.html',
      url:'/buttons'
    })
    .state('dashboard.notifications',{
      templateUrl:'views/ui-elements/notifications.html',
      url:'/notifications'
    })
    .state('dashboard.typography',{
      templateUrl:'views/ui-elements/typography.html',
      url:'/typography'
   })
    .state('dashboard.icons',{
      templateUrl:'views/ui-elements/icons.html',
      url:'/icons'
   })
    .state('dashboard.grid',{
      templateUrl:'views/ui-elements/grid.html',
      url:'/grid'
   })
  }]);

    

'use strict';

/**
 * @ngdoc overview
 * @name fbdevApp
 * @description
 * # fbdevApp
 *
 * Main module of the application.
 */
angular
  .module('fbdevApp', [
    'ngAnimate',
    'ngAria',
    'ngCookies',
    'ngMessages',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'ngTouch'
  ])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html',
        controller: 'MainCtrl',
        controllerAs: 'main'
      })
      .when('/Participer', {
        templateUrl: 'views/participer.html',
        controller: 'ParticiperCtrl',
        controllerAs: 'participer'
      })
      .otherwise({
        redirectTo: '/'
      });
  });

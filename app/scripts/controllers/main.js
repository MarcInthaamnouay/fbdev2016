'use strict';

/**
 * @ngdoc function
 * @name fbdevApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the fbdevApp
 */
angular.module('fbdevApp')
  .controller('MainCtrl', function ($location, $http) {

    var vm = this;

    vm.goToParticiper = function () {
      $http.get("/api/fbdev-back/api/v1.0/yannou")
        .success(function(response){
          console.log(response);
        })
        .error(function(response){
          console.log(response);
        });
    };
  });

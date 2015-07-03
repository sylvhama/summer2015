'use strict';
angular.module('summer2015').controller('homeController', [
  '$scope', '$location', function($scope, $location) {
    var start;
    $scope.start = function() {
      $location.path('game');
      return $scope.$apply();
    };
    start = function() {
      return $location.path('game');
    };
    return $scope.$on("cookie_created", function(event, response) {
      return start();
    });
  }
]);

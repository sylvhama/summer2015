'use strict';
angular.module('summer2015').controller('registerController', [
  '$scope', '$cookieStore', '$location', 'MemberMgmt', function($scope, $cookieStore, $location, MemberMgmt) {
    var user;
    user = $cookieStore.get('user');
    $scope.error = '';
    $scope.checkRegister = false;
    if (typeof user === "undefined" || typeof user.score === "undefined") {
      $location.path('/');
    } else if (user.phone !== null) {
      $location.path('/share');
    }
    $scope.updateUser = function($event) {
      $event.preventDefault();
      $scope.checkRegister = true;
      if ($scope.registerFields.phone.length > 0 && $scope.registerFields.agree) {
        $scope.error = '';
        user.phone = $scope.registerFields.phone;
        return MemberMgmt.doUpdateUser(user);
      }
    };
    $scope.$on("cookie_created", function(event, response) {
      return $location.path('share');
    });
    return $scope.$on('sizeErrorPhone', function(event, response) {
      return $scope.error = 'sizeErrorPhone';
    });
  }
]);

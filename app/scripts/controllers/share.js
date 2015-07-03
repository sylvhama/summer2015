'use strict';
angular.module('summer2015').controller('shareController', [
  '$scope', '$cookieStore', '$location', 'MemberMgmt', function($scope, $cookieStore, $location, MemberMgmt) {
    var user;
    user = $cookieStore.get('user');
    if (typeof user === "undefined" || typeof user.score === "undefined" || typeof user.phone === "undefined" || user.phone === null) {
      $location.path('/');
    }
    return $scope.updateShare = function() {
      if (typeof user !== "undefined") {
        return MemberMgmt.doUpdateShare(user);
      }
    };
  }
]);

'use strict';
angular.module('summer2015').controller('resultController', [
  '$scope', '$cookieStore', '$location', '$http', 'MemberMgmt', 'Facebook', function($scope, $cookieStore, $location, $http, MemberMgmt, Facebook) {
    var destinations, friends, user;
    user = $cookieStore.get('user');
    $scope.destination = {};
    $scope.mates = [];
    $scope.top3 = [];
    friends = [];
    destinations = [];
    $scope.graphReady = false;
    if (false || typeof user === "undefined" || typeof user.score === "undefined" || user.score < 0 || !Facebook.isInit()) {
      $location.path('/');
    } else {
      Facebook.getFriends();
      $http.get('./data/destinations.json').success(function(data, status) {
        var destination, i, len, results;
        destinations = data;
        MemberMgmt.selectTop3();
        results = [];
        for (i = 0, len = destinations.length; i < len; i++) {
          destination = destinations[i];
          if (parseInt(destination.id) === parseInt(user.score)) {
            results.push($scope.destination = destination);
          } else {
            results.push(void 0);
          }
        }
        return results;
      }).error(function(data, status) {
        console.log('[Error][JSON] status: ' + status);
        return ga('send', 'event', 'JSON Error', 'Get destinations ', status);
      });
    }
    $scope.updateShare = function() {
      if (typeof user !== "undefined") {
        return MemberMgmt.doUpdateShare(user);
      }
    };
    $scope.$on("fb_friends", function(event, response) {
      var friend, i, len, results;
      friends = response.data;
      results = [];
      for (i = 0, len = friends.length; i < len; i++) {
        friend = friends[i];
        friend.facebook_id = friend.id;
        results.push(MemberMgmt.selectMates(friend, user.score));
      }
      return results;
    });
    $scope.$on("selectMates", function(event, response) {
      var friend, i, len, results;
      results = [];
      for (i = 0, len = friends.length; i < len; i++) {
        friend = friends[i];
        if (friend.facebook_id === response.facebook_id) {
          $scope.mates.push(friend);
          break;
        } else {
          results.push(void 0);
        }
      }
      return results;
    });
    return $scope.$on("selectTop3", function(event, response) {
      var destination, i, j, len, len1, top;
      for (i = 0, len = response.length; i < len; i++) {
        top = response[i];
        for (j = 0, len1 = destinations.length; j < len1; j++) {
          destination = destinations[j];
          if (parseInt(destination.id) === parseInt(top.id)) {
            $scope.top3.push(destination);
            break;
          }
        }
      }
      return $scope.graphReady = true;
    });
  }
]);

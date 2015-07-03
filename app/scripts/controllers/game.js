'use strict';
angular.module('summer2015').controller('gameController', [
  '$scope', '$cookieStore', '$location', '$http', 'MemberMgmt', function($scope, $cookieStore, $location, $http, MemberMgmt) {
    var questions, score, user;
    user = $cookieStore.get('user');
    questions = [];
    $scope.question = {};
    $scope.index = 0;
    score = 0;
    $scope.mystyle = {};
    $scope.leftplane = {
      'left': '93px'
    };
    if (typeof user === "undefined") {
      $location.path('/');
    } else {
      user.score = -1;
      $http.get('./data/questions.json').success(function(data, status) {
        questions = data;
        $scope.question = questions[$scope.index];
        $scope.mystyle = {
          'background-image': "url('/images/questions/" + $scope.question.image1 + "')"
        };
        $scope.total = questions.length;
        return $scope.$broadcast('breaklines');
      }).error(function(data, status) {
        console.log('[Error][JSON] status: ' + status);
        return ga('send', 'event', 'JSON Error', 'Get questions ', status);
      });
    }
    $scope.nextQuestion = function(value) {
      var left;
      if (user.score === -1) {
        user.score = 0;
      }
      score = score + value;
      if (score < 0) {
        score = 0;
      } else if (score > questions.length) {
        score = questions.length;
      }
      if ($scope.index < questions.length - 1) {
        $scope.index++;
        $scope.question = questions[$scope.index];
        $scope.mystyle = {
          'background-image': "url('/images/questions/" + $scope.question.image1 + "')"
        };
        left = 93 + $scope.index * 75;
        return $scope.leftplane = {
          'left': left + 'px'
        };
      } else {
        user.score = score;
        return MemberMgmt.doAddParticipation(user);
      }
    };
    return $scope.$on("cookie_created", function(event, response) {
      return $location.path('result');
    });
  }
]);

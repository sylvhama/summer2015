'use strict'

angular.module('summer2015')
.controller 'gameController', ['$scope', '$cookieStore', '$location', '$http', 'MemberMgmt', ($scope, $cookieStore, $location, $http, MemberMgmt) ->
  user = $cookieStore.get('user')
  questions = []
  $scope.question = {}
  $scope.index = 0
  score = 0
  $scope.mystyle = {}
  $scope.leftplane = {'left':'93px'}

  if typeof user == "undefined"
    $location.path '/'
  else
    user.score = -1
    $http.get('./data/questions.json'
    ).success((data, status) ->
      questions = data
      $scope.question = questions[$scope.index]
      $scope.mystyle = {'background-image':"url('/images/questions/"+$scope.question.image1+"')"}
      $scope.total = questions.length
      $scope.$broadcast('breaklines')
    ).error (data, status) ->
      console.log '[Error][JSON] status: ' + status
      ga('send', 'event', 'JSON Error', 'Get questions ', status)

  $scope.nextQuestion = (value) ->
    if user.score == -1 then user.score = 0
    score = score + value
    if score < 0 then score = 0
    else if score > questions.length then score = questions.length

    if $scope.index < questions.length-1
      $scope.index++
      $scope.question = questions[$scope.index]
      $scope.mystyle = {'background-image':"url('/images/questions/"+$scope.question.image1+"')"}
      left = 93+$scope.index*75
      $scope.leftplane = {'left':left+'px'}
    else
      user.score = score
      MemberMgmt.doAddParticipation(user)

  $scope.$on "cookie_created", (event,response) ->
    $location.path 'result'
]
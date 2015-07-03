'use strict'

angular.module('summer2015')
.controller 'resultController', ['$scope', '$cookieStore', '$location', '$http', 'MemberMgmt', 'Facebook', ($scope, $cookieStore, $location, $http, MemberMgmt, Facebook) ->
  user = $cookieStore.get('user')
  $scope.destination = {}
  $scope.mates = []
  $scope.top3 = []
  friends = []
  destinations = []
  $scope.graphReady = false

  if false or typeof user == "undefined" or typeof user.score == "undefined" or user.score < 0 or !Facebook.isInit()
    $location.path '/'
  else
    Facebook.getFriends()
    $http.get('./data/destinations.json'
    ).success((data, status) ->
      destinations = data
      MemberMgmt.selectTop3()
      for destination in destinations
        if parseInt(destination.id) == parseInt(user.score)
          $scope.destination = destination
    ).error (data, status) ->
      console.log '[Error][JSON] status: ' + status
      ga('send', 'event', 'JSON Error', 'Get destinations ', status)


  $scope.updateShare = () ->
    if typeof user != "undefined"
      MemberMgmt.doUpdateShare(user)

  $scope.$on "fb_friends", (event,response) ->
    friends = response.data
    for friend in friends
      friend.facebook_id = friend.id
      MemberMgmt.selectMates(friend, user.score)

  $scope.$on "selectMates", (event,response) ->
    for friend in friends
      if friend.facebook_id == response.facebook_id
        $scope.mates.push(friend)
        break

  $scope.$on "selectTop3", (event,response) ->
    for top in response
      for destination in destinations
        if parseInt(destination.id) == parseInt(top.id)
          $scope.top3.push(destination)
          break
    $scope.graphReady = true
]
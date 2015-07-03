'use strict'

angular.module('summer2015')
.controller 'shareController', ['$scope', '$cookieStore', '$location', 'MemberMgmt', ($scope, $cookieStore, $location, MemberMgmt) ->
  user = $cookieStore.get('user')

  if typeof user == "undefined" or typeof user.score == "undefined" or typeof user.phone == "undefined" or user.phone == null
    $location.path '/'

  $scope.updateShare = () ->
    if typeof user != "undefined"
      MemberMgmt.doUpdateShare(user)
]
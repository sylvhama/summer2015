'use strict'

angular.module('summer2015')
.controller 'registerController', ['$scope', '$cookieStore', '$location', 'MemberMgmt', ($scope, $cookieStore, $location, MemberMgmt) ->
  user = $cookieStore.get('user')

  $scope.error = ''
  $scope.checkRegister = false

  if typeof user == "undefined" or typeof user.score == "undefined"
    $location.path '/'
  else if user.phone != null
    $location.path '/share'

  $scope.updateUser = ($event) ->
    $event.preventDefault()
    $scope.checkRegister = true
    if $scope.registerFields.phone.length>0 and $scope.registerFields.agree
      $scope.error = ''
      user.phone = $scope.registerFields.phone
      MemberMgmt.doUpdateUser(user)

  $scope.$on "cookie_created", (event,response) ->
    $location.path 'share'

  $scope.$on 'sizeErrorPhone', (event,response) ->
    $scope.error = 'sizeErrorPhone'
]
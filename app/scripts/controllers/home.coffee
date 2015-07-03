'use strict'

angular.module('summer2015')
.controller 'homeController', ['$scope', '$location', ($scope, $location) ->

  $scope.start = () ->
    $location.path 'game'
    $scope.$apply()

  start = () ->
    $location.path 'game'

  $scope.$on "cookie_created", (event,response) ->
    start()
]
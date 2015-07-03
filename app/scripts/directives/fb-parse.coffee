'use strict'

angular.module('summer2015').directive('fbParse', () ->
  restrict: 'C'
  link: (scope, element, attrs) ->
    FB?.XFBML.parse(element.parent()[0])
)
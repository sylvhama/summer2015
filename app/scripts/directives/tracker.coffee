'use strict'

angular.module('summer2015').directive 'tracker', [() ->
  restrict: 'A'
  link: (scope, element, attrs) ->
    $(element).on 'click', (event) ->
      ga('send', 'event', attrs.trackerType, attrs.tracker)
]

#tracker="" tracker-type=""
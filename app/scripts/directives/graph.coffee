'use strict'

angular.module('summer2015').directive 'graph', ["$window", ($window) ->
  restrict: 'C'
  link: (scope, element, attrs) ->
    animFact = ->
      elt = $(element)
      flags = $('.flag-icon')
      bars = $('.bar-content')
      scrollTop = $(this).scrollTop() + ($($window).height() / 2)
      if scrollTop > elt.offset().top
        flags.addClass('raise-the-flag')
        bars.addClass('bar-height')

    $($window).on('scroll', animFact)

    scope.$on '$destroy', (event) ->
      $($window).off('scroll', animFact)
]
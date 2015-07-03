'use strict'

angular.module('summer2015').directive 'flyingAnim', [() ->
  restrict: 'A'
  link: (scope, element, attrs) ->
    $(element).on 'click', (event) ->
      $plane = $('.question-progress .plane img')
      $plane.addClass('flying-anim')
      $newPlane = $plane.clone(true)
      $plane.before($newPlane)
      $plane.remove()

      $('.question-anim').hide().fadeIn('slow')
]
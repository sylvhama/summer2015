'use strict';
angular.module('summer2015').directive('flyingAnim', [
  function() {
    return {
      restrict: 'A',
      link: function(scope, element, attrs) {
        return $(element).on('click', function(event) {
          var $newPlane, $plane;
          $plane = $('.question-progress .plane img');
          $plane.addClass('flying-anim');
          $newPlane = $plane.clone(true);
          $plane.before($newPlane);
          $plane.remove();
          return $('.question-anim').hide().fadeIn('slow');
        });
      }
    };
  }
]);

'use strict';
angular.module('summer2015').directive('graph', [
  "$window", function($window) {
    return {
      restrict: 'C',
      link: function(scope, element, attrs) {
        var animFact;
        animFact = function() {
          var bars, elt, flags, scrollTop;
          elt = $(element);
          flags = $('.flag-icon');
          bars = $('.bar-content');
          scrollTop = $(this).scrollTop() + ($($window).height() / 2);
          if (scrollTop > elt.offset().top) {
            flags.addClass('raise-the-flag');
            return bars.addClass('bar-height');
          }
        };
        $($window).on('scroll', animFact);
        return scope.$on('$destroy', function(event) {
          return $($window).off('scroll', animFact);
        });
      }
    };
  }
]);

'use strict';
angular.module('summer2015').directive('tracker', [
  function() {
    return {
      restrict: 'A',
      link: function(scope, element, attrs) {
        return $(element).on('click', function(event) {
          return ga('send', 'event', attrs.trackerType, attrs.tracker);
        });
      }
    };
  }
]);

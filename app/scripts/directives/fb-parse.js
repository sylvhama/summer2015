'use strict';
angular.module('summer2015').directive('fbParse', function() {
  return {
    restrict: 'C',
    link: function(scope, element, attrs) {
      return typeof FB !== "undefined" && FB !== null ? FB.XFBML.parse(element.parent()[0]) : void 0;
    }
  };
});

'use strict';
angular.module('summer2015').directive('shareFb', [
  'Facebook', function(Facebook) {
    return {
      restrict: 'C',
      link: function(scope, element, attrs) {
        return $(element).on("click", function(event) {
          event.preventDefault();
          ga('send', 'event', 'share', 'facebook');
          Facebook.shareLink(attrs.shareFbLink);
          return scope.updateShare();
        });
      }
    };
  }
]);

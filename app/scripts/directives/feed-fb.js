'use strict';
angular.module('summer2015').directive('feedFb', [
  'Facebook', function(Facebook) {
    return {
      restrict: 'C',
      link: function(scope, element, attrs) {
        return $(element).on("click", function(event) {
          event.preventDefault();
          ga('send', 'event', 'share', 'facebook feed');
          Facebook.feed(attrs.feedDescription, attrs.feedName, attrs.feedPicture, attrs.feedLink);
          return scope.updateShare();
        });
      }
    };
  }
]);

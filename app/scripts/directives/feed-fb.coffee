'use strict'

angular.module('summer2015').directive 'feedFb', ['Facebook', (Facebook) ->
  restrict: 'C'
  link: (scope, element, attrs) ->
    $(element).on "click", (event) ->
      event.preventDefault()
      ga('send', 'event', 'share', 'facebook feed')
      Facebook.feed(attrs.feedDescription, attrs.feedName, attrs.feedPicture, attrs.feedLink)
      scope.updateShare()
]

#<a class="share-fb" href="#" share-fb-link=""></a>

'use strict'

angular.module('summer2015').directive 'shareFb', ['Facebook', (Facebook) ->
  restrict: 'C'
  link: (scope, element, attrs) ->
    $(element).on "click", (event) ->
      event.preventDefault()
      ga('send', 'event', 'share', 'facebook')
      Facebook.shareLink(attrs.shareFbLink)
      scope.updateShare()
]

#<a class="share-fb" href="#" share-fb-link=""></a>


#Via sharer PHP
#<a  href="https://www.facebook.com/sharer/sharer.php?u=" target="_blank"></a>


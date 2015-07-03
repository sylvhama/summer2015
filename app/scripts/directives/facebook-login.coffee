'use strict';

angular.module('summer2015')
.directive('facebookLogin', ['Facebook', '$rootScope', 'MemberMgmt', (Facebook, $rootScope, MemberMgmt) ->
  restrict: 'A'
  link: (scope, element, attrs) ->
    fbstatus = ''
    called = false

    if Facebook.isInit()
      Facebook.getLoginStatus()

    $(element).on 'click', (event) ->
      event.preventDefault()
      if fbstatus != 'connected'
        Facebook.login('user_friends')
      else if fbstatus is 'connected'
        Facebook.getInfo()

    scope.$on "fb_Login_success", () ->
      Facebook.getInfo()

    scope.$on 'fb_infos', (event,response) ->
      user = response
      if !called
        called = true
        user.device = $rootScope.media
        user.facebook_id = user.id
        MemberMgmt.doSelectUser(user)

    scope.$on "fb_statusChange", (event,response) ->
      fbstatus = response.status
]
)
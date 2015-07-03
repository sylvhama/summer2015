'use strict';
angular.module('summer2015').directive('facebookLogin', [
  'Facebook', '$rootScope', 'MemberMgmt', function(Facebook, $rootScope, MemberMgmt) {
    return {
      restrict: 'A',
      link: function(scope, element, attrs) {
        var called, fbstatus;
        fbstatus = '';
        called = false;
        if (Facebook.isInit()) {
          Facebook.getLoginStatus();
        }
        $(element).on('click', function(event) {
          event.preventDefault();
          if (fbstatus !== 'connected') {
            return Facebook.login('user_friends');
          } else if (fbstatus === 'connected') {
            return Facebook.getInfo();
          }
        });
        scope.$on("fb_Login_success", function() {
          return Facebook.getInfo();
        });
        scope.$on('fb_infos', function(event, response) {
          var user;
          user = response;
          if (!called) {
            called = true;
            user.device = $rootScope.media;
            user.facebook_id = user.id;
            return MemberMgmt.doSelectUser(user);
          }
        });
        return scope.$on("fb_statusChange", function(event, response) {
          return fbstatus = response.status;
        });
      }
    };
  }
]);

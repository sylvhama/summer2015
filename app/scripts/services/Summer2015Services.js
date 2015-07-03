'use strict';
angular.module('summer2015').factory('MemberMgmt', [
  '$rootScope', '$http', '$cookieStore', function($rootScope, $http, $cookieStore) {
    var fact;
    fact = {};
    fact.cleanUser = function(user, id) {
      var newUser;
      newUser = {};
      newUser.user_id = id;
      newUser.first_name = user.first_name;
      newUser.last_name = user.last_name;
      newUser.name = user.name;
      newUser.gender = user.gender;
      newUser.facebook_id = user.facebook_id;
      newUser.phone = null;
      newUser.device = user.device;
      newUser.shares = 0;
      return newUser;
    };
    fact.createCookie = function(user) {
      $cookieStore.remove('user');
      $cookieStore.put('user', user);
      return $rootScope.$broadcast('cookie_created');
    };
    fact.doSelectUser = function(user) {
      return $http.post('./php/do.php?r=selectUser', {
        data: {
          user: user,
          hash: 'dvHUChKocyLhG6a5jzTjXXVYbBH7nBCMHDvxxd2KXXVYbBH7nBCMHDvxxd2K'
        }
      }).success(function(data, status) {
        if (!data.error) {
          return fact.createCookie(data);
        } else if (data.error === 'noUser') {
          return fact.doAddUser(user);
        } else {
          console.log('[Error][MemberMGMT] ' + data.error);
          return ga('send', 'event', 'MemberMgmt Error', 'selectUser sql ', data.error);
        }
      }).error(function(data, status) {
        console.log('[Error][MemberMGMT] status: ' + status);
        return ga('send', 'event', 'MemberMgmt Error', 'selectUser ajax ', status);
      });
    };
    fact.selectMates = function(friend, score) {
      return $http.post('./php/do.php?r=selectMates', {
        data: {
          score: score,
          friend: friend,
          hash: 'dvHUChKocyLhG6a5jzTjXXVYbBH7nBCMHDvxxd2KXXVYbBH7nBCMHDvxxd2K'
        }
      }).success(function(data, status) {
        if (!data.error) {
          return $rootScope.$broadcast('selectMates', data);
        } else {
          console.log('[Error][MemberMGMT] ' + data.error);
          return ga('send', 'event', 'MemberMgmt Error', 'selectMates sql ', data.error);
        }
      }).error(function(data, status) {
        console.log('[Error][MemberMGMT] status: ' + status);
        return ga('send', 'event', 'MemberMgmt Error', 'selectMates ajax ', status);
      });
    };
    fact.selectTop3 = function() {
      return $http.post('./php/do.php?r=selectTop3', {
        data: {
          hash: 'dvHUChKocyLhG6a5jzTjXXVYbBH7nBCMHDvxxd2KXXVYbBH7nBCMHDvxxd2K'
        }
      }).success(function(data, status) {
        if (!data.error) {
          return $rootScope.$broadcast('selectTop3', data);
        } else {
          console.log('[Error][MemberMGMT] ' + data.error);
          return ga('send', 'event', 'MemberMgmt Error', 'selectTop3 sql ', data.error);
        }
      }).error(function(data, status) {
        console.log('[Error][MemberMGMT] status: ' + status);
        return ga('send', 'event', 'MemberMgmt Error', 'selectTop3 ajax ', status);
      });
    };
    fact.doAddUser = function(user) {
      return $http.post('./php/do.php?r=addUser', {
        data: {
          user: user,
          hash: 'dvHUChKocyLhG6a5jzTjXXVYbBH7nBCMHDvxxd2KXXVYbBH7nBCMHDvxxd2K'
        }
      }).success(function(data, status) {
        if (!data.error) {
          return fact.createCookie(fact.cleanUser(user, data));
        } else {
          console.log('[Error][MemberMGMT] ' + data.error);
          return ga('send', 'event', 'MemberMgmt Error', 'addUser sql ', data.error);
        }
      }).error(function(data, status) {
        console.log('[Error][MemberMGMT] status: ' + status);
        return ga('send', 'event', 'MemberMgmt Error', 'addUser ajax ', status);
      });
    };
    fact.doAddParticipation = function(user) {
      return $http.post('./php/do.php?r=addParticipation', {
        data: {
          user: user,
          hash: 'dvHUChKocyLhG6a5jzTjXXVYbBH7nBCMHDvxxd2KXXVYbBH7nBCMHDvxxd2K'
        }
      }).success(function(data, status) {
        if (!data.error) {
          return fact.createCookie(user);
        } else {
          console.log('[Error][MemberMGMT] ' + data.error);
          return ga('send', 'event', 'MemberMgmt Error', 'addParticipation sql ', data.error);
        }
      }).error(function(data, status) {
        console.log('[Error][MemberMGMT] status: ' + status);
        return ga('send', 'event', 'MemberMgmt Error', 'addParticipation ajax ', status);
      });
    };
    fact.doUpdateShare = function(user) {
      return $http.post("./php/do.php?r=updateShare", {
        data: {
          user: user,
          hash: 'dvHUChKocyLhG6a5jzTjXXVYbBH7nBCMHDvxxd2KXXVYbBH7nBCMHDvxxd2K'
        }
      }).success(function(data, status) {
        if (!data.error) {
          return $rootScope.$broadcast("update_share", data);
        } else {
          console.log("[Error][MemberMGMT] " + data.error);
          ga('send', 'event', 'MemberMgmt Error', 'updateShare sql ', data.error);
          return $rootScope.$broadcast(network);
        }
      }).error(function(data, status) {
        console.log("[Error][MemberMGMT] " + status);
        return ga('send', 'event', 'MemberMgmt Error', 'updateShare ajax ', status);
      });
    };
    fact.doUpdateUser = function(user) {
      return $http.post('./php/do.php?r=updateUser', {
        data: {
          user: user,
          hash: 'dvHUChKocyLhG6a5jzTjXXVYbBH7nBCMHDvxxd2KXXVYbBH7nBCMHDvxxd2K'
        }
      }).success(function(data, status) {
        if (!data.error) {
          return fact.createCookie(user);
        } else if (data.error = 'sizeErrorPhone') {
          return $rootScope.$broadcast('sizeErrorPhone');
        } else {
          console.log('[Error][MemberMGMT] ' + data.error);
          return ga('send', 'event', 'MemberMgmt Error', 'updateUser sql ', data.error);
        }
      }).error(function(data, status) {
        console.log('[Error][MemberMGMT] ' + status);
        return ga('send', 'event', 'MemberMgmt Error', 'updateUser ajax ', status);
      });
    };
    return fact;
  }
]);

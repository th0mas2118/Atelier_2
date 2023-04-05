import 'package:flutter/material.dart';

class InvitedUserModel extends ChangeNotifier {
  List invitedUsers = [];

  void addInvitedUser(user) {
    invitedUsers.add(user);
    notifyListeners();
  }

  void removeInvitedUser(user) {
    invitedUsers.remove(user);
    notifyListeners();
  }

  updateInvitedList(user) {
    if (invitedUsers.contains(user)) {
      invitedUsers.remove(user);
    } else {
      invitedUsers.add(user);
    }
    notifyListeners();
  }

  List getInvitedUsers() {
    return invitedUsers;
  }

  List getInvitedUsersForPost() {
    List users = [];
    for (var user in invitedUsers) {
      users.add({
        'user': {
          'firstname': user['firstname'],
          'lastname': user['lastname'],
          'id': user['id']
        }
      });
    }
    return users;
  }

  void clearInvitedUsers() {
    invitedUsers.clear();
    notifyListeners();
  }
}

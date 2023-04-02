import 'package:flutter/material.dart';
import 'package:dio/dio.dart';

import '../class/invitations.dart';

class InvitationsModel extends ChangeNotifier {
  Dio dio = Dio();

  final List<Invitations> _invitationsList = [];

  List<Invitations> get eventList => _invitationsList;

  int length() {
    return _invitationsList.length;
  }

  void addEvent(Invitations invitations) {
    _invitationsList.add(invitations);
    notifyListeners();
  }
}

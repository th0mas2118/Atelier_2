import 'package:flutter/material.dart';
import 'package:dio/dio.dart';

import '../class/invitations.dart';

class InvitationsModel extends ChangeNotifier {
  final String _baseUrl = 'http://api.frontoffice.reunionou:49383';
  Dio dio = Dio();

  final List<Invitations> _invitationsList = [];

  List<Invitations> get invitationsList => _invitationsList;

  int length() {
    return _invitationsList.length;
  }

  void addEvent(Invitations invitations) {
    _invitationsList.add(invitations);
    notifyListeners();
  }

  void updatInvitations(index, id, state) async {
    try {
      await dio.patch('$_baseUrl/invitations/$id', data: {'accepted': state});
      _invitationsList[index].accepted = state;
    } catch (error) {
      rethrow;
    }
  }
}

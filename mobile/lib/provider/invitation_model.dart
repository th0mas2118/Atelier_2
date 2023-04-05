import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:flutter_auth/provider/user_model.dart';
import 'package:provider/provider.dart';

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

  void updatInvitations(context, index, id, state) async {
    print(id);
    if (_invitationsList[index].accepted != true) {
      await dio.patch('$_baseUrl/events/$id/participate', data: {
        'user_id': Provider.of<UserModel>(context, listen: false).id,
        'status': 'confirmed',
        'type': 'user'
      });
      _invitationsList[index].accepted = true;
    } else {
      await dio.patch('$_baseUrl/events/$id/participate', data: {
        'user_id': Provider.of<UserModel>(context, listen: false).id,
        'status': 'declined',
        'type': 'user'
      });
      _invitationsList[index].accepted = false;
    }
  }
}

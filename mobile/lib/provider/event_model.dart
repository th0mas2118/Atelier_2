import 'package:flutter/material.dart';
import 'package:dio/dio.dart';

import '../class/event.dart';

class EventModel extends ChangeNotifier {
  Dio dio = Dio();

  final Event _event = Event('', '', '', '', '', '', [], [], '', '');

  Event get event => _event;

  //setevent
  void setEvent(Event event) {
    _event.id = event.id;
    _event.title = event.title;
    _event.description = event.description;
    _event.date = event.date;
    _event.participants = event.participants;
    _event.organizerID = event.organizerID;
    _event.organizerUsername = event.organizerUsername;
    _event.gps = event.gps;
    _event.address = event.address;
    _event.icon = event.icon;
    notifyListeners();
  }

  void unsetEvent() {
    _event.id = '';
    _event.title = '';
    _event.description = '';
    _event.date = '';
    _event.participants = [];
    _event.organizerID = '';
    _event.organizerUsername = '';
    _event.gps = [];
    _event.address = '';
    _event.icon = '';
    notifyListeners();
  }
}

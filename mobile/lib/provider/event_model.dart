import 'package:flutter/material.dart';
import 'package:dio/dio.dart';

import '../class/event.dart';

class EventModel extends ChangeNotifier {
  Dio dio = Dio();

  final List<Event> _eventList = [];

  List<Event> get eventList => _eventList;

  int length() {
    return _eventList.length;
  }

  void addEvent(Event event) {
    _eventList.add(event);
    notifyListeners();
  }
}

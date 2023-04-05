import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:http/http.dart' as http;
import 'package:path/path.dart' as path;

import '../class/event.dart';

class EventModel extends ChangeNotifier {
  Dio dio = Dio();

  final Event _event = Event('', '', '', '', '', '', [], [], '', '');
  final String _baseUrl = 'http://api.frontoffice.reunionou:49383';

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

  Future<Event> getEvent(eventId) async {
    try {
      final response = await http.get(Uri.parse('$_baseUrl/events/$eventId'));

      dynamic data = jsonDecode(response.body)['event'];
      Event event = Event(
          data['id'],
          data['title'],
          data['date'],
          data['description'],
          data['organizer']["id"],
          data['organizer']["username"],
          data['gps'],
          data['participants'],
          data['address'],
          data['icon']);
      return event;
    } catch (error) {
      rethrow;
    }
  }
}

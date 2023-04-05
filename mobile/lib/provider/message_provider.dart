import 'package:flutter/material.dart';
import 'package:dio/dio.dart';

import '../model/message.dart';

class MessageProvider extends ChangeNotifier {
  //Event Messages
  final String _getEventMessages =
      "http://api.frontoffice.reunionou:49383/messages/{id}/event";
  final String _messageUri =
      "http://docketu.iutnc.univ-lorraine.fr:62015/messages";

  //User _user;
  // late User _user;

  //Messages list
  List<EventMessage> eventMessages = [];

  //Get current _member
  // getCurrentMember() {
  //   return _member;
  // }

  ///-------------------------------------------------------------------------------------------------------------------------///
  ///***********************************************  Messages Methods  ******************************************************///
  ///-------------------------------------------------------------------------------------------------------------------------///

  //String event_id = '642accf124b181e796096862';

  //Get event messages
  Future<List<EventMessage>> getMessages(String eventId) async {
    try {
      //Call api
      var getEventMessages = _getEventMessages.replaceAll('{id}', eventId);
      var response = await Dio().get(
        getEventMessages,
        options: Options(
          headers: {
            //'Origin': "flutter",
            // 'Authorization': "Bearer " + _user.token!,
          },
        ),
      );
      if (response.statusCode == 200) {
        eventMessages = [];
        print(response.data['comments']);
        for (var msg in response.data['comments']) {
          var temp = EventMessage(
              id: msg['id'],
              eventId: msg['event_id'],
              memberId: msg['member_id'],
              firstname: msg['firstname'],
              lastname: msg['lastname'],
              content: msg['content']);
          //Add to eventMessages
          eventMessages.insert(0, temp);
        }
      }
      return eventMessages;
    } catch (e) {
      throw Exception('Failed to load event messages');
    }
  }

  // //Add message to event
  // Future<bool> addMessage(String? eventId, String message) async {
  //   //Call api
  //   try {
  //     var parsedMessage = {
  //       "event_id": eventId,
  //       "content": message,
  //       "member_id": _member.id
  //     };

  //     var response = await Dio().post(
  //       _messageUri,
  //       options: Options(
  //         headers: {
  //           'Authorization': "Bearer " + _user.token!,
  //           'Origin': "flutter",
  //           'Content-Type': 'application/json',
  //         },
  //       ),
  //       data: parsedMessage,
  //     );

  //     if (response.statusCode == 201) {
  //       await getMessages(eventId);
  //       notifyListeners();
  //       return true;
  //     }
  //     return false;
  //   } catch (e) {
  //     return false;
  //   }
  // }
}

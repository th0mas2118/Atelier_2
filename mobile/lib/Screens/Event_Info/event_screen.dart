import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:flutter_auth/Screens/Event_Info/components/event_info.dart';
import 'package:provider/provider.dart';
import '../../../constants.dart';

import '../../class/event.dart';
import '../../provider/event_model.dart';

fetchEvent(context, id) async {
  Dio dio = Dio();
  var response =
      await dio.get('http://api.frontoffice.reunionou:49383/events/$id');
  Provider.of<EventModel>(context, listen: false).setEvent(
    Event.fromJson(response.data['event']),
  );
}

class EventScreen extends StatefulWidget {
  const EventScreen({Key? key, required this.eventId}) : super(key: key);

  final String eventId;

  @override
  State<EventScreen> createState() => _EventScreenState();
}

class _EventScreenState extends State<EventScreen> {
  @override
  Widget build(BuildContext context) {
    return Consumer(
      builder: (context, value, child) {
        return Scaffold(
            appBar: AppBar(
              backgroundColor: kPrimaryColor,
              title: RichText(
                text: TextSpan(
                  text:
                      '${Provider.of<EventModel>(context).event.icon} ${Provider.of<EventModel>(context).event.title}', // emoji characters
                  style: const TextStyle(
                    fontFamily: 'EmojiOne',
                    fontSize: 30,
                    color: Colors.white,
                  ),
                ),
              ),
            ),
            body: const Padding(
              padding: EdgeInsets.all(10),
              child: EventInfo(),
            ));
      },
    );
  }
}

import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:flutter_auth/Screens/Event_Info/components/event_info.dart';
import 'package:provider/provider.dart';
import '../../../constants.dart';

import '../../class/event.dart';
import '../../provider/event_model.dart';

fetchEvent(context, id) async {
  print("fdp");
  Dio dio = Dio();
  var response =
      await dio.get('http://api.frontoffice.reunionou:49383/events/$id');
  print(response.data);
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
    print(widget.eventId);
    return Consumer<EventModel>(
      builder: (context, value, child) {
        return FutureBuilder(
            future: Provider.of<EventModel>(context, listen: false)
                .getEvent(widget.eventId),
            builder: (context, snapshot) {
              if (snapshot.connectionState == ConnectionState.waiting) {
                return const Center(
                    child: SizedBox(
                        height: 50,
                        width: 50,
                        child: CircularProgressIndicator(
                          color: Colors.purple,
                        )));
              }

              if (snapshot.hasData) {
                final event = snapshot.data as Event;
                return EventInfo(event: event);
              } else {
                return const Text('No events found.');
              }
            });
      },
    );
  }
}

import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:provider/provider.dart';

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
            title: Text(Provider.of<EventModel>(context).event.title),
          ),
          body: Column(
            children: [
              Text(Provider.of<EventModel>(context).event.title),
              Text(Provider.of<EventModel>(context).event.description),
              Text(Provider.of<EventModel>(context).event.date),
              Text(Provider.of<EventModel>(context).event.address),
              Text(Provider.of<EventModel>(context).event.organizerUsername),
            ],
          ),
        );
      },
    );
  }
}

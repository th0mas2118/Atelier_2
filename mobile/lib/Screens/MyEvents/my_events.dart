import 'package:flutter/material.dart';
import 'package:flutter_auth/Screens/MyEvents/components/event_list.dart';
import 'package:flutter_auth/class/event.dart';
import 'package:provider/provider.dart';

import '../../provider/user_model.dart';

class MyEvents extends StatefulWidget {
  const MyEvents({Key? key}) : super(key: key);

  @override
  State<MyEvents> createState() => _MyEventsState();
}

class _MyEventsState extends State<MyEvents> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(body: Consumer<UserModel>(builder: (context, value, child) {
      return Column(
        children: [
          Expanded(
              child: FutureBuilder(
            future: Provider.of<UserModel>(context, listen: false)
                .getEvents(context),
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
                final events = snapshot.data as List<Event>;
                return EventList(events: events);
              } else {
                return const Text('No events found.');
              }
            },
          )),
        ],
      );
    }));
  }
}

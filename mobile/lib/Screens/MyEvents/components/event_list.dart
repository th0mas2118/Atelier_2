import 'package:flutter/material.dart';
import 'package:flutter_auth/Screens/Event_Info/components/event_info.dart';
import 'package:flutter_auth/Screens/Event_Info/event_screen.dart';
import 'package:flutter_auth/class/event.dart';

class EventList extends StatelessWidget {
  const EventList({Key? key, required this.events}) : super(key: key);

  final List<Event> events;

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: MediaQuery.of(context).size.height,
      child: ListView.builder(
        itemCount: events.length,
        itemBuilder: (context, index) {
          return Card(
            child: ListTile(
                title: Text(events[index].title),
                subtitle: Text(events[index].organizerUsername),
                onTap: () {
                  //Push to const EventScreen()
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                        builder: (context) => Scaffold(
                            appBar: AppBar(
                              title: Text(events[index].title),
                              backgroundColor: Colors.purple,
                            ),
                            body: Material(
                                child: EventInfo(event: events[index])))),
                  );
                }),
          );
        },
      ),
    );
  }
}

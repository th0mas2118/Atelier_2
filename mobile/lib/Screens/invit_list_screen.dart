import 'package:flutter/material.dart';
import 'package:flutter_auth/components/available_badge.dart';
import 'package:flutter_auth/constants.dart';

import '../class/event.dart';

class InvitListScreen extends StatelessWidget {
  const InvitListScreen({Key? key, required this.eventList}) : super(key: key);

  final List<Event> eventList;

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
        padding: const EdgeInsets.all(8),
        itemCount: eventList.length,
        shrinkWrap: true,
        itemBuilder: (BuildContext context, int index) {
          return ListTile(
            title: Text(eventList[index].title),
            subtitle: Text('De : ${eventList[index].organizer}'),
            leading: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                AvailableBadge(
                  state: eventList[index].accepted,
                ),
              ],
            ),
            trailing: TextButton(
              //gotoevent page
              onPressed: () {},
              child: const Icon(
                Icons.arrow_forward_ios,
                color: kPrimaryColor,
              ),
            ),
          );
        });
  }
}

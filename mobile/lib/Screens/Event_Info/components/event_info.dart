import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter_auth/Screens/Event_Info/components/event_map.dart';
import 'package:flutter_auth/class/event.dart';
import 'package:provider/provider.dart';
import '../../../constants.dart';

import '../../../provider/event_model.dart';
import '../../message_screen.dart';

class EventInfo extends StatefulWidget {
  const EventInfo({Key? key, required this.event}) : super(key: key);

  final Event event;

  @override
  State<EventInfo> createState() => _EventInfoState();
}

class _EventInfoState extends State<EventInfo> {
  getConfirmatedParticipantsCount(context) {
    var list = widget.event.participants;
    return list.where((element) => element['status'] == 'confirmed').length;
  }

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
        child: Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            const Text(
              'Créateur ',
              style:
                  TextStyle(color: kPrimaryColor, fontWeight: FontWeight.bold),
            ),
            Row(
              children: [
                IconButton(
                    //go to comment page
                    onPressed: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => MessageScreen()),
                      );
                    },
                    icon: const Icon(Icons.chat_bubble, color: kPrimaryColor)),
              ],
            ),
          ],
        ),
        const Padding(padding: EdgeInsets.all(8)),
        Row(
          children: [
            Padding(
              padding: const EdgeInsets.all(8),
              child: Column(
                children: [
                  CircleAvatar(
                    radius: 30,
                    backgroundImage: NetworkImage(
                        'http://api.frontoffice.reunionou:49383/avatars/${widget.event.organizerID}/100/100'),
                  )
                ],
              ),
            ),
            Column(
              children: [
                Column(
                  children: [
                    Text(widget.event.organizerUsername),
                    const Text('Email')
                    //email firstname lastname ?
                  ],
                )
              ],
            ),
          ],
        ),
        const Padding(padding: EdgeInsets.all(8)),
        const Text('Description',
            style:
                TextStyle(fontWeight: FontWeight.bold, color: kPrimaryColor)),
        const Padding(padding: EdgeInsets.all(8)),
        Text(widget.event.description),
        const Padding(padding: EdgeInsets.all(8)),
        Text('Invités (${widget.event.participants.length})',
            style: const TextStyle(
                fontWeight: FontWeight.bold, color: kPrimaryColor)),
        const Padding(padding: EdgeInsets.all(8)),
        ListView.builder(
          shrinkWrap: true,
          itemCount: widget.event.participants.length,
          itemBuilder: (context, index) {
            return ListTile(
                contentPadding: const EdgeInsets.all(5),
                leading: CircleAvatar(
                  radius: 30,
                  backgroundImage: NetworkImage(
                      'http://api.frontoffice.reunionou:49383/avatars/${widget.event.participants[index]['user']['id']}/100/100'),
                ),
                title: Row(
                  children: [
                    Text(widget.event.participants[index]['user']['firstname'] +
                        " " +
                        widget.event.participants[index]['user']['lastname']),
                    Icon((widget.event.participants[index]['status'] ==
                            'confirmed')
                        ? Icons.check
                        : (widget.event.participants[index]['status'] ==
                                'waiting')
                            ? Icons.pending_actions
                            : Icons.close)
                  ],
                ));
          },
        ),
        const Padding(padding: EdgeInsets.all(8)),
        Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            SizedBox(
              // fullscreen width
              width: MediaQuery.of(context).size.width,
              height: MediaQuery.of(context).size.width > 600
                  ? MediaQuery.of(context).size.height * 0.75
                  : MediaQuery.of(context).size.height * 0.5,
              child: EventMap(event: widget.event),
            )
          ],
        )
      ],
    ));
  }
}

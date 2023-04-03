import 'package:flutter/material.dart';
import 'package:flutter_auth/Screens/Event_Info/components/event_map.dart';
import 'package:provider/provider.dart';
import '../../../constants.dart';

import '../../../provider/event_model.dart';

class EventInfo extends StatefulWidget {
  const EventInfo({Key? key}) : super(key: key);

  @override
  State<EventInfo> createState() => _EventInfoState();
}

class _EventInfoState extends State<EventInfo> {
  getConfirmatedParticipantsCount(context) {
    var list =
        Provider.of<EventModel>(context, listen: false).event.participants;
    return list.where((element) => element['status'] == 'confirmed').length;
  }

  bool isMap = false;

  @override
  Widget build(BuildContext context) {
    return Column(
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
                    onPressed: () {},
                    icon: Icon(Icons.map_sharp, color: kPrimaryColor)),
                IconButton(
                    onPressed: () {},
                    icon: Icon(Icons.chat_bubble, color: kPrimaryColor)),
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
                children: const [
                  CircleAvatar(
                    radius: 30,
                    backgroundImage: NetworkImage(
                        'http://api.frontoffice.reunionou:49383/avatars/test/100/100'),
                  ),
                ],
              ),
            ),
            Column(
              children: [
                Column(
                  children: [
                    Text(Provider.of<EventModel>(context)
                        .event
                        .organizerUsername),
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
        Text(Provider.of<EventModel>(context).event.description),
        const Padding(padding: EdgeInsets.all(8)),
        Text(
            'Invités (${Provider.of<EventModel>(context).event.participants.length})',
            style: const TextStyle(
                fontWeight: FontWeight.bold, color: kPrimaryColor)),
        const Padding(padding: EdgeInsets.all(8)),
        ListView.builder(
          shrinkWrap: true,
          itemCount: Provider.of<EventModel>(context).event.participants.length,
          itemBuilder: (context, index) {
            return ListTile(
                contentPadding: const EdgeInsets.all(5),
                leading: const CircleAvatar(
                  radius: 30,
                  backgroundImage: NetworkImage(
                      'http://api.frontoffice.reunionou:49383/avatars/test/100/100'),
                ),
                title: Row(
                  children: [
                    Text(Provider.of<EventModel>(context)
                        .event
                        .participants[index]['user']['username']),
                    Icon((Provider.of<EventModel>(context)
                                .event
                                .participants[index]['status'] ==
                            'confirmed')
                        ? Icons.check
                        : (Provider.of<EventModel>(context)
                                    .event
                                    .participants[index]['status'] ==
                                'waiting')
                            ? Icons.pending_actions
                            : Icons.close)
                  ],
                ));
          },
        ),
        const Padding(padding: EdgeInsets.all(8)),
        (isMap)
            ? Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  EventMap(gps: Provider.of<EventModel>(context).event.gps)
                ],
              )
            : Container(),
      ],
    );
  }
}

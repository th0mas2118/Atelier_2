import 'package:flutter/material.dart';
import 'package:flutter_auth/Screens/Event_Info/event_screen.dart';
import 'package:flutter_auth/components/available_badge.dart';
import 'package:flutter_auth/constants.dart';
import 'package:flutter_auth/provider/invitation_model.dart';
import 'package:provider/provider.dart';

import '../class/invitations.dart';

class InvitListScreen extends StatelessWidget {
  const InvitListScreen({Key? key, required this.invitationsList})
      : super(key: key);

  final List<Invitations> invitationsList;

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
        padding: const EdgeInsets.all(8),
        itemCount: invitationsList.length,
        shrinkWrap: true,
        itemBuilder: (BuildContext context, int index) {
          return ListTile(
            title: Text(invitationsList[index].title),
            subtitle: Text('De : ${invitationsList[index].organizer}'),
            leading: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                TextButton(
                  onPressed: () {
                    Provider.of<InvitationsModel>(context, listen: false)
                        .updatInvitations(index, invitationsList[index].id,
                            !invitationsList[index].accepted);
                  },
                  child: AvailableBadge(
                    state: invitationsList[index].accepted,
                  ),
                )
              ],
            ),
            trailing: TextButton(
              onPressed: () {
                fetchEvent(context, invitationsList[index].eventId);
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) {
                      return EventScreen(eventId: invitationsList[index].id);
                    },
                  ),
                );
              },
              child: const Icon(
                Icons.arrow_forward_ios,
                color: kPrimaryColor,
              ),
            ),
          );
        });
  }
}

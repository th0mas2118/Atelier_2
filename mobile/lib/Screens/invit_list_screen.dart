import 'package:flutter/material.dart';
import 'package:flutter_auth/Screens/Event_Info/event_screen.dart';
import 'package:flutter_auth/components/available_badge.dart';
import 'package:flutter_auth/constants.dart';
import 'package:flutter_auth/provider/invitation_model.dart';
import 'package:provider/provider.dart';

import '../class/invitations.dart';

class InvitListScreen extends StatefulWidget {
  const InvitListScreen({Key? key, required this.invitationsList})
      : super(key: key);

  final List<Invitations> invitationsList;

  @override
  _InvitListScreenState createState() => _InvitListScreenState();
}

class _InvitListScreenState extends State<InvitListScreen> {
  @override
  Widget build(BuildContext context) {
    return ListView.builder(
        padding: const EdgeInsets.all(8),
        itemCount: widget.invitationsList.length,
        shrinkWrap: true,
        itemBuilder: (BuildContext context, int index) {
          return ListTile(
            title: Text(widget.invitationsList[index].title),
            subtitle: Text('De : ${widget.invitationsList[index].organizer}'),
            leading: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                TextButton(
                  onPressed: () {
                    setState(() {
                      Provider.of<InvitationsModel>(context, listen: false)
                          .updatInvitations(
                              context,
                              index,
                              widget.invitationsList[index].eventId,
                              !widget.invitationsList[index].accepted);
                    });
                  },
                  child: AvailableBadge(
                    state: widget.invitationsList[index].accepted,
                  ),
                )
              ],
            ),
            trailing: TextButton(
              onPressed: () {
                fetchEvent(context, widget.invitationsList[index].eventId);
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) {
                      return EventScreen(
                          eventId: widget.invitationsList[index].id);
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

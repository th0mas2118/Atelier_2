import 'package:flutter/material.dart';
import 'package:flutter_auth/provider/invited_user_model.dart';
import 'package:provider/provider.dart';

class ResearchResult extends StatefulWidget {
  const ResearchResult({Key? key, required this.users}) : super(key: key);

  final users;

  @override
  _ResearchResultState createState() => _ResearchResultState();
}

class _ResearchResultState extends State<ResearchResult> {
  @override
  Widget build(BuildContext context) {
    return ListView.builder(
        itemCount: widget.users.length,
        itemBuilder: (context, index) {
          return Consumer<InvitedUserModel>(
            builder: (context, value, child) {
              return ListTile(
                title: Row(children: [
                  CircleAvatar(
                    radius: 15,
                    backgroundImage: NetworkImage(
                        'http://api.frontoffice.reunionou:49383/avatars/${widget.users[index]['index']}/200/200'),
                  ),
                  const Padding(padding: EdgeInsets.all(8.0)),
                  Text(widget.users[index]['firstname']),
                  const Padding(padding: EdgeInsets.all(4.0)),
                  Text(widget.users[index]['lastname']),
                  const Padding(padding: EdgeInsets.all(4.0)),
                  IconButton(
                    onPressed: () {
                      Provider.of<InvitedUserModel>(context, listen: false)
                          .updateInvitedList(widget.users[index]);
                    },
                    icon: (!value.invitedUsers.contains(widget.users[index]))
                        ? const Icon(
                            Icons.add,
                            color: Colors.green,
                          )
                        : const Icon(
                            Icons.remove,
                            color: Colors.red,
                          ),
                  )
                ]),
              );
            },
          );
        });
  }
}

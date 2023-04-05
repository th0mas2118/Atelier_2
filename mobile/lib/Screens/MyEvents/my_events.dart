import 'package:flutter/material.dart';
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
          Text('Mes événements'),
          FutureBuilder(
            future: Provider.of<UserModel>(context, listen: false)
                .getEvents(context),
            builder: (context, snapshot) {
              return const Padding(padding: EdgeInsets.all(10));
            },
          ),
        ],
      );
    }));
  }
}

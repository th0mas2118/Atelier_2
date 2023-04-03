import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../../provider/user_model.dart';

class MyInfo extends StatefulWidget {
  const MyInfo({Key? key}) : super(key: key);

  @override
  State<MyInfo> createState() => _MyInfoState();
}

class _MyInfoState extends State<MyInfo> {
  @override
  Widget build(BuildContext context) {
    return Column(
      mainAxisAlignment: MainAxisAlignment.start,
      crossAxisAlignment: CrossAxisAlignment.center,
      mainAxisSize: MainAxisSize.max,
      children: [
        const SizedBox(
          height: 20,
        ),
        Consumer<UserModel>(
          builder: (context, user, child) {
            return Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    CircleAvatar(
                      radius: 60,
                      backgroundImage: NetworkImage(
                          'http://api.frontoffice.reunionou:49383/avatars/${user.id}/200/200'),
                    ),
                  ],
                ),
                const SizedBox(
                  height: 20,
                ),
                // Text("ID : ${user.id}"),
                Text("Nom d'utilisateur : ${user.username}"),
                const Padding(padding: EdgeInsets.all(5)),
                Text('Prénom : ${user.firstname}'),
                const Padding(padding: EdgeInsets.all(5)),
                Text('Nom : ${user.lastname}'),
                const Padding(padding: EdgeInsets.all(5)),
                Text('Email : ${user.email}'),
                const Padding(padding: EdgeInsets.all(5)),
                Text('Adresse : ${user.adresse}'),
                const SizedBox(
                  height: 20,
                ),
              ],
            );
          },
        ),
      ],
    );
  }
}

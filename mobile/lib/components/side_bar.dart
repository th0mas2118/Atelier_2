import 'package:flutter/material.dart';
import 'package:flutter_auth/Screens/MyPage/components/my_info.dart';
import 'package:flutter_auth/Screens/invit_list_screen.dart';
import 'package:provider/provider.dart';
import '../../../constants.dart';

import '../../../provider/user_model.dart';
import '../../class/invitations.dart';
import '../Screens/MyPage/mypage_screen.dart';
import '../Screens/messageScreen.dart';

class SideBar extends StatefulWidget {
  const SideBar({Key? key}) : super(key: key);

  @override
  State<SideBar> createState() => _SideBarState();
}

class _SideBarState extends State<SideBar> {
  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: ListView(
        children: [
          SizedBox(
            height: 64,
            child: DrawerHeader(
              decoration: const BoxDecoration(
                color: kPrimaryColor,
              ),
              child: ElevatedButton(
                style: ElevatedButton.styleFrom(
                  backgroundColor: kPrimaryLightColor,
                ),
                onPressed: () {
                  Provider.of<UserModel>(context, listen: false)
                      .logout(context);
                },
                child: const Text('Logout',
                    style: TextStyle(
                      color: Colors.black,
                    )),
              ),
            ),
          ),
          SizedBox(
            width: 64,
            child: ElevatedButton(
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) {
                        //Ou tu veux aller
                        return MessageScreen();
                      },
                    ),
                  );
                },
                child: const Text('Message')),
          ),
          SizedBox(
            width: 64,
            child: ElevatedButton(
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) {
                        //Ou tu veux aller
                        return const MyPage();
                      },
                    ),
                  );
                },
                child: const Text('Créer un évènement')),
          ),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: const [
              Text(
                'Mes Invitations',
                style: TextStyle(
                  fontSize: 20,
                ),
              ),
            ],
          ),
          FutureBuilder(
            future: Provider.of<UserModel>(context, listen: false)
                .getInvit(context),
            builder: (context, snapshot) {
              if (snapshot.hasData) {
                return InvitListScreen(
                    invitationsList: snapshot.data as List<Invitations>);
              } else if (snapshot.hasError) {
                return Text("${snapshot.error}");
              }
              return const Center(child: CircularProgressIndicator());
            },
          ),
        ],
      ),
    );
  }
}

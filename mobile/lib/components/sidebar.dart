import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../Screens/invit_list_screen.dart';
import '../class/invitations.dart';
import '../provider/user_model.dart';

class Sidebar extends StatelessWidget {
  const Sidebar(
      {Key? key,
      required this.onItemTapped,
      required this.body,
      required this.title})
      : super(key: key);
  final Widget body;
  final String title;
  final Function onItemTapped;
  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          title: Text(title),
          centerTitle: true,
          backgroundColor: Colors.purple,
          automaticallyImplyLeading: false,
        ),
        body: body,
        endDrawer: Consumer<UserModel>(builder: (context, value, child) {
          return value.isLoggedIn
              ? Drawer(
                  child: ListView(
                    padding: EdgeInsets.zero,
                    children: [
                      SizedBox(
                          height: 64,
                          child: DrawerHeader(
                              decoration: const BoxDecoration(
                                color: Colors.purple,
                              ),
                              child: Stack(
                                children: <Widget>[
                                  Positioned(
                                    top: -8,
                                    right: 0,
                                    child: IconButton(
                                      icon: const Icon(
                                        Icons.logout,
                                        color: Colors.white,
                                      ),
                                      onPressed: () {
                                        Provider.of<UserModel>(context,
                                                listen: false)
                                            .logout(context);
                                      },
                                    ),
                                  ),
                                  Positioned(
                                    top: -8,
                                    left: 0,
                                    child: IconButton(
                                      icon: const Icon(
                                        Icons.newspaper,
                                        color: Colors.white,
                                      ),
                                      onPressed: () {
                                        Navigator.pop(context);
                                        onItemTapped(3);
                                      },
                                    ),
                                  ),
                                ],
                              ))),
                      ListTile(
                        title: const Text('Accueil'),
                        onTap: () {
                          Navigator.pop(context);
                          onItemTapped(0);
                        },
                      ),
                      ListTile(
                        title: const Text('Mon profil'),
                        onTap: () {
                          Navigator.pop(context);
                          onItemTapped(1);
                        },
                      ),
                      ListTile(
                        title: const Text('Mes évenements'),
                        onTap: () {
                          Navigator.pop(context);
                          onItemTapped(2);
                        },
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
                                invitationsList:
                                    snapshot.data as List<Invitations>);
                          } else if (snapshot.hasError) {
                            return Text("${snapshot.error}");
                          }
                          return const Center(
                              child: CircularProgressIndicator());
                        },
                      ),
                    ],
                  ),
                )
              : const Padding(padding: EdgeInsets.zero);
        }));
  }
}

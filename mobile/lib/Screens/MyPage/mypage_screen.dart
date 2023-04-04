import 'package:flutter/material.dart';
import 'package:flutter_auth/Screens/MyPage/components/my_info.dart';
import 'package:flutter_auth/Screens/invit_list_screen.dart';
import 'package:provider/provider.dart';
import '../../../constants.dart';
import '../../components/side_bar.dart';

import '../../../provider/user_model.dart';
import '../../class/invitations.dart';
import 'components/modify_my_info.dart';

class MyPage extends StatefulWidget {
  const MyPage({Key? key}) : super(key: key);

  @override
  State<MyPage> createState() => _MyPageState();
}

class _MyPageState extends State<MyPage> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          centerTitle: true,
          title: const Text('MyPage'),
          backgroundColor: kPrimaryColor,
          automaticallyImplyLeading: false,
        ),
        endDrawer: const SideBar(),
        body: Column(
          children: [
            const MyInfo(),
            const SizedBox(
              height: 20,
            ),
            FractionallySizedBox(
              widthFactor: 0.8,
              child: ElevatedButton(
                style: ElevatedButton.styleFrom(
                  backgroundColor: kPrimaryColor,
                ),
                onPressed: () {
                  Navigator.push(context,
                      MaterialPageRoute(builder: (context) => ModifyMyInfo()));
                },
                child: const Text('Modifier mes informations'),
              ),
            ),
          ],
        ));
  }
}

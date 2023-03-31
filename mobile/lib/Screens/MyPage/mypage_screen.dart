import 'package:flutter/material.dart';
import 'package:flutter_auth/Screens/MyPage/components/my_info.dart';
import 'package:provider/provider.dart';
import '../../../constants.dart';

import '../../../provider/user_model.dart';

class MyPage extends StatefulWidget {
  const MyPage({Key? key}) : super(key: key);

  @override
  State<MyPage> createState() => _MyPageState();
}

test() {}

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
      endDrawer: Drawer(
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
          ],
        ),
      ),
      body: const MyInfo(),
    );
  }
}

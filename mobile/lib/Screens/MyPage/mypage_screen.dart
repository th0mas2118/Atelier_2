import 'package:flutter/material.dart';
import 'package:flutter_auth/Screens/MyPage/components/my_info.dart';
import '../../../constants.dart';

class MyPage extends StatefulWidget {
  const MyPage({Key? key}) : super(key: key);

  @override
  State<MyPage> createState() => _MyPageState();
}

class _MyPageState extends State<MyPage> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
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
              Navigator.pushNamed(context, "/editprofile");
            },
            child: const Text('Modifier mes informations'),
          ),
        ),
      ],
    ));
  }
}

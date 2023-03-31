import 'package:flutter/material.dart';

class MyInfo extends StatefulWidget {
  const MyInfo({Key? key}) : super(key: key);

  @override
  State<MyInfo> createState() => _MyInfoState();
}

test() {}

class _MyInfoState extends State<MyInfo> {
  @override
  Widget build(BuildContext context) {
    return Column(
      mainAxisAlignment: MainAxisAlignment.start,
      crossAxisAlignment: CrossAxisAlignment.center,
      mainAxisSize: MainAxisSize.max,
      children: [
        Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Image.network(
              'https://images.unsplash.com/photo-1547721064-da6cfb341d50',
              width: 280.0,
            ),
            const Text('isdgfhfngdfdfsgd'),
            const Text('name'),
            const Text('lastname'),
            const Text('username'),
            const Text('email'),
            const Text('adres')
          ],
        ),
      ],
    );
  }
}

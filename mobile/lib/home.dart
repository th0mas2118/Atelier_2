import 'package:flutter/material.dart';
import 'package:flutter_auth/Screens/Create_Event/components/create_event_form.dart';
import 'package:flutter_auth/Screens/Event_Info/components/event_info.dart';
import 'package:flutter_auth/Screens/MyEvents/my_events.dart';
import 'package:flutter_auth/Screens/MyPage/mypage_screen.dart';
import 'package:flutter_auth/Screens/Welcome/welcome_screen.dart';
import 'package:flutter_auth/components/sidebar.dart';

class MyHomePageState extends StatefulWidget {
  const MyHomePageState({Key? key}) : super(key: key);

  @override
  _MyHomePageStateState createState() => _MyHomePageStateState();
}

class _MyHomePageStateState extends State<MyHomePageState> {
  int _selectedIndex = 0;

  void onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
      print(_selectedIndex);
    });
  }

  final List<Widget> _widgetOptions = <Widget>[
    const WelcomeScreen(),
    const MyPage(),
    const EventInfo(),
    CreateEventForm(),
    MyEvents()
  ];

  final List<String> _widgetTitles = <String>[
    'Accueil',
    'Mon compte',
    'Mon événement',
    'Créer un évènement',
    "Mes évenements"
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: Center(
            child: Sidebar(
                onItemTapped: onItemTapped,
                body: _widgetOptions.elementAt(_selectedIndex),
                title: _widgetTitles.elementAt(_selectedIndex))));
  }
}

import 'package:flutter/material.dart';
import './screens/home_page.dart';
import 'package:mobile/screens/profile_view_screen.dart';
import 'package:provider/provider.dart';


void main() {
  runApp(const MyApp());
  //   MultiProvider(
  //     providers: [
        
  //     ],
  //     child: MyApp(),
  //   ),
  // );
}
class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Reunionou',
      
      theme: ThemeData(
        primarySwatch: Colors.grey,
      ),
      home: const MyHomePage(title: 'REUNIONOU'),
    );
  }
}


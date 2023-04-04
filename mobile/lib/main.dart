import 'package:flutter/material.dart';
import 'package:flutter_auth/Screens/MyPage/mypage_screen.dart';
import 'package:flutter_auth/Screens/Welcome/welcome_screen.dart';
import 'package:flutter_auth/constants.dart';
import 'package:flutter_auth/provider/event_model.dart';
import 'package:flutter_auth/provider/message_provider.dart';
import 'package:provider/provider.dart';
import '../provider/user_model.dart';
import 'provider/invitation_model.dart';
import 'package:shared_preferences/shared_preferences.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  SharedPreferences prefs = await SharedPreferences.getInstance();
  bool isLoggedIn = prefs.getBool('isLoggedIn') ?? false;
  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (context) => UserModel()),
        ChangeNotifierProvider(create: (context) => InvitationsModel()),
        ChangeNotifierProvider(create: (context) => EventModel()),
        ChangeNotifierProvider(create: (context) => Message_provider())
      ],
      child: MyApp(isLoggedIn: isLoggedIn),
    ),
  );
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key, required this.isLoggedIn}) : super(key: key);

  final bool isLoggedIn;

  @override
  Widget build(BuildContext context) {
    Widget homeScreen;
    if (isLoggedIn) {
      // Si l'utilisateur est connecté, afficher votre page ici
      Provider.of<UserModel>(context, listen: false).loadUser();

      homeScreen = const MyPage();
    } else {
      // Sinon, afficher la page de bienvenue
      homeScreen = const WelcomeScreen();
    }

    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Reunionou',
      theme: ThemeData(
          primaryColor: kPrimaryColor,
          scaffoldBackgroundColor: Colors.white,
          elevatedButtonTheme: ElevatedButtonThemeData(
            style: ElevatedButton.styleFrom(
              elevation: 0,
              backgroundColor: kPrimaryColor,
              shape: const StadiumBorder(),
              maximumSize: const Size(double.infinity, 56),
              minimumSize: const Size(double.infinity, 56),
            ),
          ),
          inputDecorationTheme: const InputDecorationTheme(
            filled: true,
            fillColor: kPrimaryLightColor,
            iconColor: kPrimaryColor,
            prefixIconColor: kPrimaryColor,
            contentPadding: EdgeInsets.symmetric(
                horizontal: defaultPadding, vertical: defaultPadding),
            border: OutlineInputBorder(
              borderRadius: BorderRadius.all(Radius.circular(30)),
              borderSide: BorderSide.none,
            ),
          )),
      home: homeScreen,
    );
  }
}

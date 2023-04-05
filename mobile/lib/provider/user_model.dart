import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:flutter_auth/home.dart';
import 'package:http/http.dart' as http;
import 'package:path/path.dart' as path;
import 'dart:io';

import 'package:flutter_auth/Screens/MyPage/mypage_screen.dart';
import 'package:flutter_auth/Screens/Welcome/welcome_screen.dart';
import 'package:jwt_decoder/jwt_decoder.dart';

import '../Screens/Login/login_screen.dart';
import '../class/invitations.dart';
import 'invitation_model.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../class/event.dart';

class UserModel extends ChangeNotifier {
  final String _baseUrl = 'http://api.frontoffice.reunionou:49383';
  Dio dio = Dio();

  String username = '';
  String firstname = '';
  String lastname = '';
  String email = '';
  String password = '';
  String accesToken = '';
  String refreshToken = '';
  String adresse = '';
  String id = '';
  bool isLoggedIn = false;
  File? image = null;

  Map<String, dynamic> get log => {
        'username': username,
        'firstname': firstname,
        'lastname': lastname,
        'email': email,
        'accesToken': accesToken,
        'refreshToken': refreshToken,
        'id': id,
        'adresse': adresse,
        'isLoggedIn': isLoggedIn, // ajout de la variable
        'image': image,
      };
  String get loggedId => id;

  void setUser(vid, vusername, vfirstname, vlastname, vemail, vadresse,
      vaccesToken, vrefreshToken) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    await prefs.setString('id', vid);
    await prefs.setString('username', vusername);
    await prefs.setString('firstname', vfirstname);
    await prefs.setString('lastname', vlastname);
    await prefs.setString('email', vemail);
    await prefs.setString('adresse', vadresse);
    await prefs.setString('accesToken', vaccesToken);
    await prefs.setString('refreshToken', vrefreshToken);
    await prefs.setBool('isLoggedIn', true); // ajout de la variable

    id = vid;
    username = vusername;
    firstname = vfirstname;
    lastname = vlastname;
    email = vemail;
    adresse = vadresse;
    accesToken = vaccesToken;
    refreshToken = vrefreshToken;
    isLoggedIn = true; // mise à jour de la variable
    notifyListeners();
  }

  void removeUser() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    await prefs.clear();

    id = '';
    username = '';
    firstname = '';
    lastname = '';
    email = '';
    adresse = '';
    accesToken = '';
    refreshToken = '';
    isLoggedIn = false; // mise à jour de la variable
    notifyListeners();
  }

  void setImage(image) {
    this.image = image;
    notifyListeners();
  }

  void updateUser(vemail, vfirstname, vlastname, vadresse) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    try {
      await dio.put('$_baseUrl/user/$id',
          options: Options(headers: {'Authorization': 'Bearer $accesToken'}),
          data: {
            'email': vemail,
            'firstname': vfirstname,
            'lastname': vlastname,
            'adresse': vadresse
          });
      if (image != null) {
        print(image!.path);
        FormData formData = FormData.fromMap({
          'file': await MultipartFile.fromFile(image!.path,
              filename: path.basename(image!.path))
        });
        await dio.post('$_baseUrl/avatars/$id',
            options:
                Options(headers: {'Authorization': 'Bearer $log.accesToken'}),
            data: formData);
      }
    } catch (error) {
      rethrow;
    }
    email = vemail;
    firstname = vfirstname;
    lastname = vlastname;
    adresse = vadresse;
    await prefs.setString('firstname', vfirstname);
    await prefs.setString('lastname', vlastname);
    await prefs.setString('email', vemail);
    await prefs.setString('adresse', vadresse);
    notifyListeners();
  }

  Future<void> login(String username, String password, $context) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    var auth = 'Basic ${base64Encode(utf8.encode('$username:$password'))}';
    try {
      final response = await dio.post('$_baseUrl/signin',
          options: Options(headers: <String, String>{'authorization': auth}));
      var decodetoken = JwtDecoder.decode(response.data['user']['acces_token']);
      setUser(
          decodetoken['uid'],
          decodetoken['username'],
          decodetoken['firstname'],
          decodetoken['lastname'],
          decodetoken['usermail'],
          decodetoken['adresse'] ?? "",
          response.data['user']['acces_token'],
          response.data['user']['refresh_token']);
      Navigator.push(
        $context,
        MaterialPageRoute(
          builder: (context) {
            return const MyHomePageState();
          },
        ),
      );
    } catch (error) {
      String? id = prefs.getString('id');
      String? username = prefs.getString('username');
      String? firstname = prefs.getString('firstname');
      String? lastname = prefs.getString('lastname');
      String? email = prefs.getString('email');
      String? adresse = prefs.getString('adresse');
      String? accesToken = prefs.getString('accesToken');
      String? refreshToken = prefs.getString('refreshToken');
      bool? isLoggedIn = prefs.getBool('isLoggedIn');

      if (id != null &&
          username != null &&
          firstname != null &&
          lastname != null &&
          email != null &&
          accesToken != null &&
          refreshToken != null) {
        setUser(id, username, firstname, lastname, email, adresse, accesToken,
            refreshToken);
      }

      rethrow;
    }
  }

  Future<void> register(String $email, String $username, String $firstname,
      String $lastname, String $password, $context) async {
    try {
      await dio.post('$_baseUrl/signup', data: {
        'email': $email,
        'username': $username,
        'firstname': $firstname,
        'lastname': $lastname,
        'password': $password
      });
      Navigator.push(
        $context,
        MaterialPageRoute(
          builder: (context) {
            return const LoginScreen();
          },
        ),
      );
    } catch (error) {
      rethrow;
    }
  }

  Future<void> logout($context) async {
    try {
      await dio.post('$_baseUrl/signout',
          options: Options(headers: <String, String>{
            'authorization': 'Bearer $accesToken'
          }));
      removeUser();
      notifyListeners();
      Navigator.push(
        $context,
        MaterialPageRoute(
          builder: (context) {
            return const WelcomeScreen();
          },
        ),
      );
    } catch (error) {
      rethrow;
    }
  }

  Future<void> loadUser() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? id = prefs.getString('id');
    String? username = prefs.getString('username');
    String? firstname = prefs.getString('firstname');
    String? lastname = prefs.getString('lastname');
    String? email = prefs.getString('email');
    String? adresse = prefs.getString('adresse');
    String? accesToken = prefs.getString('accesToken');
    String? refreshToken = prefs.getString('refreshToken');
    bool? isLoggedIn = prefs.getBool('isLoggedIn');

    if (id != null &&
        username != null &&
        firstname != null &&
        lastname != null &&
        email != null &&
        accesToken != null &&
        refreshToken != null) {
      setUser(id, username, firstname, lastname, email, adresse ?? "",
          accesToken, refreshToken);
    }
  }

  Future<List<Invitations>> getInvit(context) async {
    try {
      final response =
          await http.get(Uri.parse('$_baseUrl/user/$id/invitations'));
      List<Invitations> list = [];
      for (var element in jsonDecode(response.body)['invitations']) {
        list.add(Invitations.fromJson(element));
        Provider.of<InvitationsModel>(context, listen: false)
            .addEvent(Invitations.fromJson(element));
      }
      return list;
    } catch (error) {
      rethrow;
    }
  }

  Future<List<Event>> getEvents(context) async {
    try {
      final response = await http.get(Uri.parse('$_baseUrl/user/$id/events'));
      List<Event> list = [];
      for (var element in jsonDecode(response.body)['events']) {
        Event event = Event(
            element.id,
            element.title,
            element.date,
            element.description,
            element.organizer.id,
            element.organizer.username,
            element.gps,
            element.participants,
            element.address,
            element.icon);
        print(event);
        list.add(event);
      }
      print(list[0]);
      return list;
    } catch (error) {
      rethrow;
    }
  }
}

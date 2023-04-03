import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:http/http.dart' as http;

import 'package:flutter_auth/Screens/MyPage/mypage_screen.dart';
import 'package:flutter_auth/Screens/Welcome/welcome_screen.dart';
import 'package:jwt_decoder/jwt_decoder.dart';

import '../Screens/Login/login_screen.dart';
import '../class/invitations.dart';
import 'invitation_model.dart';
import 'package:provider/provider.dart';

class UserModel extends ChangeNotifier {
  final String _baseUrl = 'http://api.frontoffice.reunionou:49383';
  Dio dio = Dio();

  bool connected = false;
  String username = '';
  String firstname = '';
  String lastname = '';
  String email = '';
  String password = '';
  String accesToken = '';
  String refreshToken = '';
  String adresse = '';
  String id = '';

  Map<String, dynamic> get log => {
        'username': username,
        'firstname': firstname,
        'lastname': lastname,
        'email': email,
        'accesToken': accesToken,
        'refreshToken': refreshToken,
        'id': id,
        'adresse': adresse,
        'connected': connected
      };
  String get loggedId => id;

  void setUser(vid, vusername, vfirstname, vlastname, vemail, vadresse,
      vaccesToken, vrefreshToken) {
    connected = true;
    id = vid;
    username = vusername;
    firstname = vfirstname;
    lastname = vlastname;
    email = vemail;
    adresse = vadresse;
    accesToken = vaccesToken;
    refreshToken = vrefreshToken;
    notifyListeners();
  }

  void removeUser() {
    connected = false;
    id = '';
    username = '';
    firstname = '';
    lastname = '';
    email = '';
    adresse = '';
    accesToken = '';
    refreshToken = '';
    notifyListeners();
  }

  void updateUser(vemail, vfirstname, vlastname, vadresse) async {
    try {
      await dio.put('$_baseUrl/user/$id',
          options: Options(headers: {'Authorization': 'Bearer $accesToken'}),
          data: {
            'email': vemail,
            'firstname': vfirstname,
            'lastname': vlastname,
            'adresse': vadresse
          });
    } catch (error) {
      rethrow;
    }
    email = vemail;
    firstname = vfirstname;
    lastname = vlastname;
    adresse = vadresse;
    notifyListeners();
  }

  Future<void> login(String username, String password, $context) async {
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
            return const MyPage();
          },
        ),
      );
    } catch (error) {
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
}

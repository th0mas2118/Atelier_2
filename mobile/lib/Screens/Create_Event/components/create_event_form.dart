import 'package:flutter/material.dart';
import 'package:flutter_auth/Screens/Create_Event/components/research_result.dart';
import 'package:flutter_auth/provider/invited_user_model.dart';
import 'package:flutter_datetime_picker/flutter_datetime_picker.dart';

import '../../../constants.dart';
import 'package:provider/provider.dart';
import '../../../provider/user_model.dart';
import 'package:dio/dio.dart';

Dio dio = Dio();

const String _baseUrl = 'http://api.frontoffice.reunionou:49383';
List userList = [];
getUsers(search, context) async {
  userList.clear();
  final response = await dio.get('$_baseUrl/users?search=$search');
  //add to user
  for (var element in response.data['users']) {
    userList.add(element);
  }
  String userId = Provider.of<UserModel>(context, listen: false).id;
  userList = userList.where((element) => element['id'] != userId).toList();
  return userList;
}

createEvent(context, title, description, date, adresse) async {
  var user = Provider.of<UserModel>(context, listen: false).log;
  var gpsData = await dio.get('https://geocode.maps.co/search?q=$adresse');
  var gps = [gpsData.data[0]['lat'], gpsData.data[0]['lon']];

  var body = {
    'title': title,
    "description": description,
    "date": date,
    'address': adresse,
    'gps': gps,
    'organizer': {
      'firstname': user['firstname'],
      'lastname': user['lastname'],
      'email': user['email'],
      'username': user['username'],
    },
    'isPrivate': false,
    'participants': Provider.of<InvitedUserModel>(context, listen: false)
        .getInvitedUsersForPost()
  };
  final response = dio.post(
    '$_baseUrl/events',
    data: body,
    options: Options(headers: {
      'Authorization': "Bearer $user['accesToken']",
      'Content-Type': 'application/json'
    }),
  );
  print(response);
}

class CreateEventForm extends StatefulWidget {
  const CreateEventForm({
    Key? key,
  }) : super(key: key);

  @override
  _CreateEventFormState createState() => _CreateEventFormState();
}

class _CreateEventFormState extends State<CreateEventForm> {
  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();
  TextEditingController titleController = TextEditingController();
  TextEditingController descriptionController = TextEditingController();
  TextEditingController dateController = TextEditingController();
  TextEditingController addressController = TextEditingController();
  TextEditingController searchController = TextEditingController();

  Dio dio = Dio();
  DateTime eventDate = DateTime.now();
  bool showResult = false;

  // createEvent()async{}

  @override
  Widget build(BuildContext context) {
    return Form(
      child: Column(
        children: [
          const SizedBox(height: defaultPadding),
          TextFormField(
            controller: titleController,
            textInputAction: TextInputAction.next,
            cursorColor: kPrimaryColor,
            decoration: const InputDecoration(
              hintText: "Titre de l'événement",
              prefixIcon: Padding(
                padding: EdgeInsets.all(defaultPadding),
                child: Icon(Icons.title_outlined),
              ),
            ),
          ),
          const SizedBox(height: defaultPadding),
          TextFormField(
              controller: addressController,
              textInputAction: TextInputAction.next,
              cursorColor: kPrimaryColor,
              decoration: const InputDecoration(
                hintText: "Adresse",
                prefixIcon: Padding(
                  padding: EdgeInsets.all(defaultPadding),
                  child: Icon(Icons.description),
                ),
              )),
          const SizedBox(height: defaultPadding),
          TextFormField(
              controller: descriptionController,
              textInputAction: TextInputAction.next,
              cursorColor: kPrimaryColor,
              decoration: const InputDecoration(
                hintText: "Description de l'événement",
                prefixIcon: Padding(
                  padding: EdgeInsets.all(defaultPadding),
                  child: Icon(Icons.description),
                ),
              )),
          const SizedBox(height: defaultPadding),
          ElevatedButton(
              onPressed: () {
                DatePicker.showDateTimePicker(
                  context,
                  showTitleActions: true,
                  onChanged: (date) {
                    eventDate = date;
                  },
                  onConfirm: (date) {
                    eventDate = date;
                  },
                  currentTime: DateTime.now(),
                  locale: LocaleType.fr,
                );
              },
              child: const Text('Sélectionner une date')),
          const SizedBox(height: defaultPadding),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            children: [
              Expanded(
                child: TextFormField(
                    controller: searchController,
                    textInputAction: TextInputAction.next,
                    cursorColor: kPrimaryColor,
                    decoration: const InputDecoration(
                      hintText: "Rechercher des participants",
                      prefixIcon: Padding(
                        padding: EdgeInsets.all(defaultPadding),
                      ),
                    )),
              ),
              SizedBox(
                  width: 50,
                  child: ElevatedButton(
                      onPressed: () {
                        setState(() {
                          showResult = true;
                        });
                      },
                      child: const Icon(Icons.search))),
            ],
          ),
          (showResult)
              ? SizedBox(
                  height: 130,
                  child: FutureBuilder(
                    future: getUsers(searchController.text, context),
                    builder: (context, snapshot) {
                      if (snapshot.hasData) {
                        return ResearchResult(users: snapshot.data as List);
                      } else if (snapshot.hasError) {
                        return Text("${snapshot.error}");
                      }
                      return const Center(child: CircularProgressIndicator());
                    },
                  ),
                )
              : Container(),
          const SizedBox(height: defaultPadding),
          Hero(
              tag: "createEventButton",
              child: ElevatedButton(
                onPressed: () {
                  createEvent(
                      context,
                      titleController.text,
                      descriptionController.text,
                      eventDate.toString(),
                      addressController.text);
                },
                style: ButtonStyle(
                  backgroundColor:
                      MaterialStateProperty.all<Color>(kPrimaryColor),
                ),
                child: const Text(
                  "Créer l'événement",
                  style: TextStyle(color: Colors.white),
                ),
              )),
        ],
      ),
    );
  }
}

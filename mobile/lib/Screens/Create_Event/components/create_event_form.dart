import 'package:flutter/material.dart';
import 'package:flutter_datetime_picker/flutter_datetime_picker.dart';

import '../../../constants.dart';
import 'package:provider/provider.dart';
import '../../../provider/user_model.dart';
import 'package:dio/dio.dart';

class CreateEventForm extends StatelessWidget {
  CreateEventForm({
    Key? key,
  }) : super(key: key);

  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();
  TextEditingController titleController = TextEditingController();
  TextEditingController descriptionController = TextEditingController();
  TextEditingController dateController = TextEditingController();
  TextEditingController addressController = TextEditingController();

  final String _baseUrl = 'http://api.frontoffice.reunionou:49383';
  Dio dio = Dio();
  DateTime eventDate = DateTime.now();

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
                ;
              },
              child: const Text('Selection la date')),
          const SizedBox(height: defaultPadding),
          Hero(
              tag: "createEventButton",
              child: ElevatedButton(
                onPressed: () {
                  Provider.of<UserModel>(context, listen: false).login(
                      emailController.text, passwordController.text, context);
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

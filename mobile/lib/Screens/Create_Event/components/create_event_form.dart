import 'package:flutter/material.dart';

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

  // createEvent()async  (

  // )

  @override
  Widget build(BuildContext context) {
    return Form(
      
      
      child:Column(
        

        children: [
          const SizedBox(height: defaultPadding),
          TextFormField(
            controller: titleController,
            textInputAction: TextInputAction.next,
            cursorColor: kPrimaryColor,
            decoration: const InputDecoration(
              hintText: "Description de l'événement",
              prefixIcon: Padding(
                padding: EdgeInsets.all(defaultPadding),
                child: Icon(Icons.title_outlined),
            ),
          ),
          ),
          const SizedBox(height: defaultPadding),

          Padding(
            padding: const EdgeInsets.symmetric(vertical: defaultPadding),
            child: TextFormField(
              controller: dateController,
              textInputAction: TextInputAction.next,
              cursorColor: kPrimaryColor,
              decoration: const InputDecoration(
                hintText: "Date de l'événement",
                prefixIcon: Padding(
                  padding: EdgeInsets.all(defaultPadding),
                  child: Icon(Icons.calendar_month_rounded),
                ),
                
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.symmetric(vertical: defaultPadding),
            child: TextFormField(
              controller: descriptionController,
              textInputAction: TextInputAction.next,
              cursorColor: kPrimaryColor,
              decoration: const InputDecoration(
                hintText: "Description de l'événement",
                prefixIcon: Padding(
                  padding: EdgeInsets.all(defaultPadding),
                  child: Icon(Icons.description),
                ),
              ) 
                child: Icon(Icons.description),
              ),
            
          ),
        ),),
        Padding(
          padding: const EdgeInsets.symmetric(vertical: defaultPadding),
          child: TextFormField(
            controller: addressController,
            textInputAction: TextInputAction.next,
            cursorColor: kPrimaryColor,
            decoration: const InputDecoration(
              hintText: "Adresse de l'événement",
              prefixIcon: Padding(
                padding: EdgeInsets.all(defaultPadding),
                child: Icon(Icons.location_on),
              ),
            ),
          ),
          const SizedBox(height: defaultPadding),
          Hero(
            tag: "login_btn",
            child: SizedBox(width: 300,
              child:ElevatedButton(
              onPressed: () {
                Provider.of<UserModel>(context, listen: false).login(
                    emailController.text, passwordController.text, context);
              },
              style: ButtonStyle(
                backgroundColor: MaterialStateProperty.all<Color>(kPrimaryColor),
              ),
              child: const Text(
                "Créer l'événement",
                style: TextStyle(color: Colors.white),
              ),
        ),
        const SizedBox(height: defaultPadding),
        Hero(
          tag: "login_btn",
          child: ElevatedButton(
            onPressed: () {
              Provider.of<UserModel>(context, listen: false).login(
                  emailController.text, passwordController.text, context);
            },
            style: ButtonStyle(
              backgroundColor: MaterialStateProperty.all<Color>(kPrimaryColor),
            ),
            child: const Text(
              "Créer l'événement",
              style: TextStyle(color: Colors.white),
            ),
            )
          ),
          const SizedBox(height: defaultPadding),
        ],
      ),
        ),
        const SizedBox(height: defaultPadding),
      ]),
    );
  }
}

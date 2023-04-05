import 'dart:io';

import 'package:flutter/material.dart';
import '../../../../constants.dart';
import 'package:provider/provider.dart';
import '../../../provider/user_model.dart';
import '../../MyPage/mypage_screen.dart';
import 'package:image_picker/image_picker.dart';

// ignore: must_be_immutable
class ModifyMyInfo extends StatelessWidget {
  ModifyMyInfo({Key? key}) : super(key: key);

  TextEditingController emailController = TextEditingController();
  TextEditingController firstnameController = TextEditingController();
  TextEditingController firstNameController = TextEditingController();
  TextEditingController lastNameController = TextEditingController();
  TextEditingController adresseController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Material(
      child: Consumer<UserModel>(
        builder: (context, value, child) {
          emailController.text = value.email;
          firstnameController.text = value.firstname;
          lastNameController.text = value.lastname;
          adresseController.text = value.adresse;
          return child!;
        },
        child: Form(
            child: Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Padding(padding: EdgeInsets.all(20)),
              const Text('Modifier mes informations',
                  style: TextStyle(fontSize: 20)),
              const Padding(padding: EdgeInsets.all(10)),
              const Text('Adresse mail :'),
              const Padding(padding: EdgeInsets.all(5)),
              TextFormField(
                controller: emailController,
                keyboardType: TextInputType.emailAddress,
                textInputAction: TextInputAction.next,
                cursorColor: kPrimaryColor,
                decoration: const InputDecoration(
                  hintText: "Votre email",
                  prefixIcon: Padding(
                    padding: EdgeInsets.all(defaultPadding),
                    child: Icon(Icons.mail_lock_outlined),
                  ),
                ),
              ),
              const Padding(padding: EdgeInsets.all(10)),
              const Text('Prénom :'),
              const Padding(padding: EdgeInsets.all(5)),
              TextFormField(
                controller: firstnameController,
                keyboardType: TextInputType.name,
                textInputAction: TextInputAction.next,
                cursorColor: kPrimaryColor,
                decoration: const InputDecoration(
                  hintText: "Votre prénom",
                  prefixIcon: Padding(
                    padding: EdgeInsets.all(defaultPadding),
                    child: Icon(Icons.person),
                  ),
                ),
              ),
              const Padding(padding: EdgeInsets.all(10)),
              const Text('Nom :'),
              const Padding(padding: EdgeInsets.all(5)),
              TextFormField(
                controller: lastNameController,
                keyboardType: TextInputType.name,
                textInputAction: TextInputAction.next,
                cursorColor: kPrimaryColor,
                decoration: const InputDecoration(
                  hintText: "Votre nom",
                  prefixIcon: Padding(
                    padding: EdgeInsets.all(defaultPadding),
                    child: Icon(Icons.person),
                  ),
                ),
              ),
              const Padding(padding: EdgeInsets.all(10)),
              const Text('Adresse :'),
              const Padding(padding: EdgeInsets.all(5)),
              TextFormField(
                controller: adresseController,
                keyboardType: TextInputType.streetAddress,
                textInputAction: TextInputAction.next,
                cursorColor: kPrimaryColor,
                decoration: const InputDecoration(
                  hintText: "Votre adresse",
                  prefixIcon: Padding(
                    padding: EdgeInsets.all(defaultPadding),
                    child: Icon(Icons.location_on),
                  ),
                ),
              ),
              const Padding(padding: EdgeInsets.all(10)),
              ElevatedButton(
                onPressed: () async {
                  final pickedFile =
                      await ImagePicker().getImage(source: ImageSource.gallery);
                  if (pickedFile != null) {
                    // TODO: envoyer l'image à l'API
                    Provider.of<UserModel>(context, listen: false)
                        .setImage(File(pickedFile.path));
                  }
                },
                child: const Text('Sélectionner une image'),
              ),
              const Padding(padding: EdgeInsets.all(10)),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                children: [
                  Expanded(
                      child: FractionallySizedBox(
                    widthFactor: 0.7,
                    child: ElevatedButton(
                      onPressed: () {
                        Navigator.pop(context);
                      },
                      child: const Text('Annuler'),
                    ),
                  )),
                  Expanded(
                      child: FractionallySizedBox(
                    widthFactor: 0.7,
                    child: ElevatedButton(
                      onPressed: () async {
                        Provider.of<UserModel>(context, listen: false)
                            .updateUser(
                          emailController.text,
                          firstnameController.text,
                          lastNameController.text,
                          adresseController.text,
                        );
                        Navigator.pop(context);
                      },
                      child: const Text('Valider'),
                    ),
                  )),
                ],
              )
            ],
          ),
        )),
      ),
    );
  }
}

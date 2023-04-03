import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../../components/already_have_an_account_acheck.dart';
import '../../../constants.dart';
import '../../Login/login_screen.dart';
import '../../../provider/user_model.dart';

// ignore: must_be_immutable
class SignUpForm extends StatelessWidget {
  SignUpForm({
    Key? key,
  }) : super(key: key);

  TextEditingController emailController = TextEditingController();
  TextEditingController userNameController = TextEditingController();
  TextEditingController fristNameController = TextEditingController();
  TextEditingController lastNameController = TextEditingController();
  TextEditingController passwordController = TextEditingController();
  TextEditingController passwordConfirmController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Form(
      child: Column(
        children: [
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
          TextFormField(
            controller: userNameController,
            keyboardType: TextInputType.emailAddress,
            textInputAction: TextInputAction.next,
            cursorColor: kPrimaryColor,
            decoration: const InputDecoration(
              hintText: "Votre nom d'utilisateur",
              prefixIcon: Padding(
                padding: EdgeInsets.all(defaultPadding),
                child: Icon(Icons.person),
              ),
            ),
          ),
          const Padding(padding: EdgeInsets.all(10)),
          TextFormField(
            controller: fristNameController,
            keyboardType: TextInputType.emailAddress,
            textInputAction: TextInputAction.next,
            cursorColor: kPrimaryColor,
            decoration: const InputDecoration(
              hintText: "Votre nom",
              prefixIcon: Padding(
                padding: EdgeInsets.all(defaultPadding),
                child: Icon(Icons.person_2_outlined),
              ),
            ),
          ),
          const Padding(padding: EdgeInsets.all(10)),
          TextFormField(
            controller: lastNameController,
            keyboardType: TextInputType.emailAddress,
            textInputAction: TextInputAction.next,
            cursorColor: kPrimaryColor,
            decoration: const InputDecoration(
              hintText: "Votre prénom",
              prefixIcon: Padding(
                padding: EdgeInsets.all(defaultPadding),
                child: Icon(Icons.person_2_outlined),
              ),
            ),
          ),
          const Padding(padding: EdgeInsets.all(10)),
          TextFormField(
            obscureText: true,
            controller: passwordController,
            keyboardType: TextInputType.emailAddress,
            textInputAction: TextInputAction.next,
            cursorColor: kPrimaryColor,
            decoration: const InputDecoration(
              hintText: "Mot de passe",
              prefixIcon: Padding(
                padding: EdgeInsets.all(defaultPadding),
                child: Icon(Icons.lock),
              ),
            ),
          ),
          const Padding(padding: EdgeInsets.all(10)),
          TextFormField(
            controller: passwordConfirmController,
            keyboardType: TextInputType.emailAddress,
            textInputAction: TextInputAction.next,
            cursorColor: kPrimaryColor,
            obscureText: true,
            decoration: const InputDecoration(
              hintText: "Confirmation",
              prefixIcon: Padding(
                padding: EdgeInsets.all(defaultPadding),
                child: Icon(Icons.verified_user_outlined),
              ),
            ),
          ),
          const Padding(padding: EdgeInsets.all(10)),
          const SizedBox(height: defaultPadding / 2),
          ElevatedButton(
            onPressed: () {
              if (passwordController.text != passwordConfirmController.text) {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(
                    content: Text("Les mots de passe ne sont pas identiques"),
                  ),
                );
                return;
              }
              if (emailController.text.isEmpty ||
                  userNameController.text.isEmpty ||
                  fristNameController.text.isEmpty ||
                  lastNameController.text.isEmpty ||
                  passwordController.text.isEmpty ||
                  passwordConfirmController.text.isEmpty) {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(
                    content: Text("Veuillez remplir tous les champs"),
                  ),
                );
                return;
              }
              Provider.of<UserModel>(context, listen: false).register(
                  emailController.text,
                  userNameController.text,
                  fristNameController.text,
                  lastNameController.text,
                  passwordController.text,
                  context);
            },
            child: Text("S'inscrire".toUpperCase()),
          ),
          const SizedBox(height: defaultPadding),
          AlreadyHaveAnAccountCheck(
            login: false,
            press: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) {
                    return const LoginScreen();
                  },
                ),
              );
            },
          ),
        ],
      ),
    );
  }
}

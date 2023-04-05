import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../provider/message_model.dart';
import '../provider/user_model.dart';
import '../class/message.dart';

class MessageScreen extends StatefulWidget {
  @override
  _MessageScreenState createState() => _MessageScreenState();
}

class _MessageScreenState extends State<MessageScreen> {
  final _textController = TextEditingController();

  List<EventMessage> _messages = [];

  void _handleSubmitted(String text) async {
    _textController.clear();
    final messageAdd = Provider.of<MessageModel>(context, listen: false);
    // if (await messageAdd.addMessage(
    //     context, '642accf124b181e796096862', text)) {
    //   List<EventMessage> messages =
    //       await messageAdd.getMessages('642accf124b181e796096862');
    //   setState(() {
    //     _messages = messages;
    //     _messages.insert(
    //       0,
    //       EventMessage(
    //         id: '',
    //         eventId: '642accf124b181e796096862',
    //         memberId: '',
    //         firstname: '',
    //         lastname: '',
    //         content: text,
    //       ),
    //     );
    //   });
    // } else {
    List<EventMessage> messages =
        await messageAdd.getMessages('642accf124b181e796096862');
    setState(() {
      _messages = messages;
      _messages.insert(
        0,
        EventMessage(
          id: '',
          eventId: '642accf124b181e796096862',
          memberId: '',
          firstname: '',
          lastname: '',
          content: text,
        ),
      );
    });
    //}
  }

  Widget _buildTextComposer() {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 8.0),
      child: Row(
        children: <Widget>[
          Flexible(
            child: TextField(
              controller: _textController,
              onSubmitted: _handleSubmitted,
              decoration: const InputDecoration.collapsed(
                hintText: "Envoyer le message",
              ),
            ),
          ),
          Container(
            margin: const EdgeInsets.symmetric(horizontal: 4.0),
            child: IconButton(
              icon: const Icon(Icons.send),
              onPressed: () => _handleSubmitted(_textController.text),
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final messageProvider = Provider.of<MessageModel>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('My Messages'),
        backgroundColor: Colors.purple,
      ),
      body: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisSize: MainAxisSize.max,
        children: [
          const SizedBox(height: 20),
          FutureBuilder<List<EventMessage>>(
            future: messageProvider.getMessages('642accf124b181e796096862'),
            builder: (context, snapshot) {
              if (snapshot.connectionState == ConnectionState.waiting) {
                return const CircularProgressIndicator();
              }

              if (snapshot.hasError) {
                return Text('Failed to fetch message: ${snapshot.error}');
              }

              final messages = snapshot.data;

              if (messages == null || messages.isEmpty) {
                return const Text('No message available');
              }
              return Expanded(
                child: ListView.builder(
                  itemCount: messages.length,
                  reverse: true,
                  itemBuilder: (context, index) {
                    final message = messages[index];
                    final user =
                        Provider.of<UserModel>(context, listen: false).log;
                    return Padding(
                        padding: const EdgeInsets.symmetric(
                            vertical: 5.0, horizontal: 10.0),
                        child: Row(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // CircleAvatar(
                            //   radius: 20.0,
                            //   backgroundImage: NetworkImage(message.memberAvatar),
                            // ),
                            const SizedBox(width: 10.0),
                            Expanded(
                              child: Column(
                                crossAxisAlignment:
                                    message.memberId == user['id']
                                        ? CrossAxisAlignment.end
                                        : CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    '${message.firstname.toLowerCase()} ${message.lastname.toLowerCase()}',
                                    style: TextStyle(
                                      color: Color.fromARGB(255, 90, 32, 100),
                                      fontSize: 16.0,
                                    ),
                                  ),
                                  Container(
                                    width:
                                        MediaQuery.of(context).size.width * 0.5,
                                    decoration: BoxDecoration(
                                        color: message.memberId == user['id']
                                            ? Colors.purple
                                            : Colors.grey,
                                        borderRadius:
                                            BorderRadius.circular(10.0)),
                                    child: Column(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      children: [
                                        Row(
                                          mainAxisAlignment:
                                              MainAxisAlignment.spaceBetween,
                                        ),
                                        const SizedBox(height: 5.0),
                                        Padding(
                                          padding: const EdgeInsets.all(8.0),
                                          child: Text(message.content,
                                              style: TextStyle(
                                                  color: Colors.white)),
                                        )
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ));
                  },
                ),
              );
            },
          ),
          _buildTextComposer(),
        ],
      ),
    );
  }
}

class EventMessage {
  const EventMessage({
    this.id,
    required this.event_id,
    required this.member_id,
    required this.content,
    required this.firstname,
    required this.lastname,
  });
  final String? id;
  final String event_id;
  final String member_id;
  final String content;
  final String firstname;
  final String? lastname;

//From map
  EventMessage.fromMap(Map<String, dynamic> res)
      : id = res["id"],
        event_id = res['event_id'],
        member_id = res['member_id'],
        content = res['content'],
        firstname = res['firstname'],
        lastname = res['lastname'];

  //To json
  String toJson() {
    return '{id: $id, event_id: $event_id, member_id: $member_id, content: $content, firstname: $firstname, lastname: $lastname}';
  }
}

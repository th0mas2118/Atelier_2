class EventMessage {
  const EventMessage({
    this.id,
    required this.eventId,
    required this.memberId,
    required this.content,
    required this.firstname,
    required this.lastname,
  });
  final String? id;
  final String eventId;
  final String memberId;
  final String content;
  final String firstname;
  final String lastname;

//From map
  EventMessage.fromMap(Map<String, dynamic> res)
      : id = res["id"],
        eventId = res['event_id'],
        memberId = res['member_id'],
        content = res['content'],
        firstname = res['firstname'],
        lastname = res['lastname'];

  //To json
  String toJson() {
    return '{id: $id, event_id: $eventId, member_id: $memberId, content: $content, firstname: $firstname, lastname: $lastname}';
  }
}

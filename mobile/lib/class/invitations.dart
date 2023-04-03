class Invitations {
  String id;
  String eventId;
  String title;
  String organizer;
  bool accepted;

  Invitations(this.id, this.eventId, this.title, this.organizer, this.accepted);

  factory Invitations.fromJson(Map<String, dynamic> json) {
    return Invitations(
      json['id'],
      json['event_id'],
      json['event_title'],
      json['organizer']['username'],
      json['accepted'],
    );
  }
}

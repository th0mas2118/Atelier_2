class Invitations {
  String id;
  String title;
  String organizer;
  bool accepted;

  Invitations(this.id, this.title, this.organizer, this.accepted);

  factory Invitations.fromJson(Map<String, dynamic> json) {
    return Invitations(
      json['event_id'],
      json['event_title'],
      json['organizer']['username'],
      json['accepted'],
    );
  }
}

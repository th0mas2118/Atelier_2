class Event {
  String id;
  String title;
  String organizer;
  bool accepted;

  Event(this.id, this.title, this.organizer, this.accepted);

  factory Event.fromJson(Map<String, dynamic> json) {
    return Event(
      json['event_id'],
      json['event_title'],
      json['organizer']['username'],
      json['accepted'],
    );
  }
}

class Event {
  String id;
  String title;
  String date;
  String description;
  String organizerID;
  String organizerUsername;
  List gps;
  List participants;
  String address;
  String icon;

  Event(
      this.id,
      this.title,
      this.date,
      this.description,
      this.organizerID,
      this.organizerUsername,
      this.gps,
      this.participants,
      this.address,
      this.icon);

  factory Event.fromJson(Map<String, dynamic> json) {
    return Event(
        json['id'],
        json['title'],
        json['date'],
        json['description'],
        json['organizer']['id'],
        json['organizer']['username'],
        json['gps'],
        json['participants'],
        json['address'],
        json['icon']);
  }
}

import 'package:flutter/material.dart';
import 'package:flutter_auth/class/event.dart';
import 'package:flutter_auth/provider/event_model.dart';
import 'package:provider/provider.dart';

import 'package:flutter_map/flutter_map.dart';
// ignore: depend_on_referenced_packages
import 'package:latlong2/latlong.dart';

class EventMap extends StatefulWidget {
  const EventMap({Key? key, required this.event}) : super(key: key);

  final Event event;

  @override
  State<EventMap> createState() => _EventMapState();
}

class _EventMapState extends State<EventMap> {
  @override
  Widget build(BuildContext context) {
    try {
      double lat = double.parse(widget.event.gps[0].toStringAsFixed(6));
      double long = double.parse(widget.event.gps[1].toStringAsFixed(6));

      return Expanded(
          child: FlutterMap(
              options: MapOptions(
                  center: LatLng(lat, long), zoom: 13, minZoom: 1, maxZoom: 18),
              children: [
            TileLayer(
              urlTemplate: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
              userAgentPackageName: 'com.example.app',
            ),
            MarkerLayer(
              markers: [
                Marker(
                  width: 80.0,
                  height: 80.0,
                  point: LatLng(lat, long),
                  builder: (ctx) => const Icon(
                    Icons.location_on,
                    color: Colors.red,
                  ),
                ),
              ],
            )
          ]));
    } catch (e) {
      print("=====================================");
      print(e);
      print("=====================================");
      return Container();
    }
  }
}

import 'package:flutter/material.dart';
import 'package:flutter_auth/provider/event_model.dart';
import 'package:provider/provider.dart';

import 'package:flutter_map/flutter_map.dart';
// ignore: depend_on_referenced_packages
import 'package:latlong2/latlong.dart';

class EventMap extends StatelessWidget {
  const EventMap({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Consumer<EventModel>(
      builder: (context, value, child) {
        return Expanded(
            child: FlutterMap(
                options: MapOptions(
                    center: LatLng(value.event.gps[0], value.event.gps[1]),
                    zoom: 16,
                    minZoom: 1,
                    maxZoom: 16),
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
                    point: LatLng(value.event.gps[0], value.event.gps[1]),
                    builder: (ctx) => const Icon(
                      Icons.location_on,
                      color: Colors.red,
                    ),
                  ),
                ],
              )
            ]));
      },
    );
  }
}

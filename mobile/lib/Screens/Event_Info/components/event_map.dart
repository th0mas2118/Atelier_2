import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'package:flutter_map/flutter_map.dart';
// ignore: depend_on_referenced_packages
import 'package:latlong2/latlong.dart';

class EventMap extends StatelessWidget {
  const EventMap({Key? key, required this.gps}) : super(key: key);

  final List gps;

  @override
  Widget build(BuildContext context) {
    print(gps);
    return Container(
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(10),
        ),
        height: 400,
        width: 400,
        child: FlutterMap(
            options: MapOptions(
                center: LatLng(gps[0], gps[1]),
                zoom: 13,
                minZoom: 1,
                maxZoom: 18),
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
                    point: LatLng(gps[0], gps[1]),
                    builder: (ctx) => const Icon(
                      Icons.location_on,
                      color: Colors.red,
                    ),
                  ),
                ],
              )
            ]));
  }
}

import 'package:flutter/material.dart';

class AvailableBadge extends StatelessWidget {
  const AvailableBadge({Key? key, required this.state}) : super(key: key);

  final bool state;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 20,
      height: 20,
      decoration: BoxDecoration(
        color: state ? Colors.green : Colors.red,
        shape: BoxShape.circle,
      ),
    );
  }
}

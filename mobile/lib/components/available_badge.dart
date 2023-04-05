import 'package:flutter/material.dart';

class AvailableBadge extends StatefulWidget {
  const AvailableBadge({Key? key, required this.state}) : super(key: key);

  final bool state;

  @override
  _AvailableBadgeState createState() => _AvailableBadgeState();
}

class _AvailableBadgeState extends State<AvailableBadge> {
  late bool _currentState;

  @override
  void initState() {
    super.initState();
    _currentState = widget.state;
  }

  @override
  Widget build(BuildContext context) {
    setState(() {});
    return Container(
      width: 30,
      height: 30,
      decoration: BoxDecoration(
        color: _currentState ? Colors.green : Colors.red,
        shape: BoxShape.circle,
      ),
    );
  }
}

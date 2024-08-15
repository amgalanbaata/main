import 'package:flutter/material.dart';
import 'package:my_flutter_project/screens/menu.dart';

class ActivityPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pop(context);
          },
        ),
        title: Text('үйл ажиллагааны чиглэл'),
      ),
      body: Center(
        child: Text('үйл ажиллагааны чиглэл'),
      ),
    );
  }
}
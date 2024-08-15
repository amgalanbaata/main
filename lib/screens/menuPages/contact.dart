import 'package:flutter/material.dart';
import 'package:my_flutter_project/screens/menu.dart';

class ContactPage extends StatelessWidget {
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
        title: Text('Холбоо барих'),
      ),
      body: Center(
        child: Text('Холбоо барих'),
      ),
    );
  }
}
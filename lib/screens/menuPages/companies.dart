import 'package:flutter/material.dart';

class CompaniesPage extends StatelessWidget {
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
        title: Text('Хамтрагч байгууллага'),
      ),
      body: Center(
        child: Text('Хамтрагч байгууллага'),
      ),
    );
  }
}
import 'package:flutter/material.dart';
import 'package:my_flutter_project/screens/menu.dart';

class Listpage extends StatelessWidget {
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
        title: Text('жагсаалт'),
      ),
      body: Center(
        child: Text('Хөрсний дээж шинжилгээний лабораторийн мэдээлэл '),
      ),
    );
  }
}
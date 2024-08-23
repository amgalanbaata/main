import 'package:flutter/material.dart';
import 'package:my_flutter_project/screens/bottonMenuPages/post_form.dart';
import 'package:my_flutter_project/screens/bottonMenuPages/ubSoil.dart';
import 'package:my_flutter_project/screens/home.dart';
import 'package:my_flutter_project/screens/menuPages/userInformation.dart';

class MainApp extends StatefulWidget {
  const MainApp({Key? key}) : super(key: key);

  @override
  _MainAppState createState() {
    print("SSSSS");
     return _MainAppState();
  }
}

class _MainAppState extends State<MainApp> {
  int _selectedIndex = 0;

  final List<Widget> _pages = <Widget>[
    Home(),
    PostForm(),
    Userinformation()
  ];

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
  }

  @override
  void initState() {
    super.initState();
    _onItemTapped(_selectedIndex);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _pages[_selectedIndex],
      key: GlobalKey(),
      bottomNavigationBar: BottomNavigationBar(
        items: const <BottomNavigationBarItem>[
          BottomNavigationBarItem(
            icon: Icon(Icons.home),
            label: 'Нүүр хуудас',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.add),
            label: 'Мэдэгдэл',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.history),
            label: 'Түүх',
          ),
        ],
        currentIndex: _selectedIndex,
        selectedItemColor: Color.fromARGB(255, 39, 141, 90),
        onTap: _onItemTapped,
      ),
    );
  }
}

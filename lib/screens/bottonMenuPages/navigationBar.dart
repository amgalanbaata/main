import 'package:flutter/material.dart';
import 'package:ubsoil/screens/bottonMenuPages/post_form.dart';
import 'package:ubsoil/screens/bottonMenuPages/home.dart';
import 'package:ubsoil/screens/bottonMenuPages/userInformation.dart';
import 'package:ubsoil/main.dart';
import 'package:shared_preferences/shared_preferences.dart';

class MainApp extends StatefulWidget {
  const MainApp({Key? key}) : super(key: key);

  @override
  _MainAppState createState() {
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
    _checkCommitteeOfficer();
    _onItemTapped(_selectedIndex);
  }

  Future<void> _checkCommitteeOfficer() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    bool? officeStatus = prefs.getBool('committeeOfficer');

    if(officeStatus != null) {
      setState(() {
        committeeOfficer = officeStatus;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _pages[_selectedIndex],
      key: GlobalKey(),
      bottomNavigationBar: committeeOfficer ?
       BottomNavigationBar(
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
      ) : null
    );
  }
}

import 'package:flutter/material.dart';
import 'package:my_flutter_project/screens/rightMenuPages/electronicLibrery.dart';
import 'package:my_flutter_project/screens/rightMenuPages/contactUs.dart';
import 'package:my_flutter_project/screens/rightMenuPages/documents.dart';
import 'package:my_flutter_project/screens/rightMenuPages/profile.dart';
import 'package:my_flutter_project/screens/rightMenuPages/soilPollution.dart';
import 'package:my_flutter_project/screens/rightMenuPages/standarts.dart';
import 'package:my_flutter_project/screens/rightMenuPages/laboratoryList.dart';
import 'package:shared_preferences/shared_preferences.dart';

class Menu extends StatefulWidget {
  @override
  _MenuState createState() => _MenuState();
}

class _MenuState extends State<Menu> {
  String _userName = '';

  @override
  void initState() {
    super.initState();
    _loadUserName();
  }

  Future<void> _loadUserName() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
      _userName = prefs.getString('name') ?? "user";
    });
  }

  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          DrawerHeader(
            decoration: BoxDecoration(
              color: Color.fromARGB(255, 39, 141, 90),
            ),
            child: Row(
              children: [
                Image(
                  height: 50,
                  image: NetworkImage('https://environment.ub.gov.mn/assets/images/resources/NBOG-logo.png'),
                ),
              ],
            ),
          ),
          ListTile(
            leading: Icon(Icons.person),
            title: Text(_userName),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => Profile()),
              );
            },
          ),
          ListTile(
            leading: Icon(Icons.people),
            title: Text('Бидний тухай'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => ContactPage()),
              );
            },
          ),
          ListTile(
            leading: Icon(Icons.library_books),
            title: Text('Цахим номын сан'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => ElectronicLibraryPage()),
              );
            },
          ),
          ListTile(
            leading: Icon(Icons.width_normal),
            title: Text('Стандартууд'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => StandartsPageView()),
              );
            },
          ),
          ListTile(
            leading: Icon(Icons.store),
            title: Text('Хөрсний шинжилгээ'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => UbSoilWebView()),
              );
            },
          ),
          ListTile(
            leading: Icon(Icons.document_scanner_outlined),
            title: Text('Баримт бичгүүд'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => ListPage()),
              );
            },
          ),
          ListTile(
            leading: Icon(Icons.water),
            title: Text('Хөрсний бохирдол'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => SoilWebView()),
              );
            },
          ),
        ],
      ),
    );
  }
}

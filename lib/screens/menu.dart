import 'package:flutter/material.dart';
import 'package:my_flutter_project/screens/menuPages/news.dart';
import 'package:my_flutter_project/screens/menuPages/companies.dart';
import 'package:my_flutter_project/screens/menuPages/contactUs.dart';
import 'package:my_flutter_project/screens/menuPages/listPage.dart';
import 'package:my_flutter_project/screens/menuPages/profile.dart';
import 'package:my_flutter_project/screens/menuPages/soil.dart';
import 'package:my_flutter_project/screens/menuPages/ubSoil.dart';
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
            leading: Icon(Icons.contact_emergency),
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
            leading: Icon(Icons.house),
            title: Text('Хамтрагч байгууллага'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => CompaniesPage()),
              );
            },
          ),
          ListTile(
            leading: Icon(Icons.phone),
            title: Text('Жагсаалт'),
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
            title: Text('Хөрсний хэв шинж'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => SoilWebView()),
              );
            },
          ),
          // ListTile(
          //   leading: Icon(Icons.newspaper),
          //   title: Text('Мэдээ'),
          //   onTap: () {
          //     Navigator.pop(context);
          //     Navigator.push(
          //       context,
          //       MaterialPageRoute(builder: (context) => NewsWebView()),
          //     );
          //   },
          // ),
        ],
      ),
    );
  }
}

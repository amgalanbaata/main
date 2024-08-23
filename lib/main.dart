import 'package:flutter/material.dart';
import 'package:my_flutter_project/screens/bottonMenuPages/navigationBar.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:my_flutter_project/screens/register.dart';

// const String apiUrl = 'https://nbog.susano-tech.mn/';
const String apiUrl = 'http://192.168.50.243:8000/';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      home: const App(),
    );
  }
}

class App extends StatelessWidget {
  const App({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<bool>(
      future: _checkUserLoggedIn(),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        } else {
          if (snapshot.data!) {
            return const MainApp();
          } else {
            return const Register();
          }
        }
      },
    );
  }

  Future<bool> _checkUserLoggedIn() async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    final String? name = prefs.getString('name');
    // final String? number = prefs.getString('number');
    return name != null;
  }
}
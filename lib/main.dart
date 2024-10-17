import 'package:flutter/material.dart';
import 'package:ubsoil/screens/bottonMenuPages/navigationBar.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:ubsoil/screens/instructions.dart';
import 'package:ubsoil/screens/register.dart';

// const String apiUrl = 'https://nbog.susano-tech.mn/';
// const String apiUrl = 'http://192.168.50.243:8000/';
const String apiUrl = 'https://ubsoil.environment.ub.gov.mn/';
bool committeeOfficer = false;
List<dynamic> typeNameGlobal = [];
List<dynamic> statusNameGlobal = [];
bool _isAgreed = false;

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
    return FutureBuilder<Map<String, dynamic>>(
      future: _checkUserStatus(),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        } else {
          final bool isLoggedIn = snapshot.data!['isLoggedIn'];
          final bool isAgreed = snapshot.data!['isAgreed'];
          
          if (isLoggedIn  && isAgreed) {
            return const MainApp();
          } else if (isLoggedIn && !isAgreed) {
            return InstructionsPageView();
          } else {
            return const Register();
          }
        }
      },
    );
  }

  Future<Map<String, dynamic>> _checkUserStatus() async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    final bool isAgreed = prefs.getBool('isAgreed') ?? false;
    final String? name = prefs.getString('name');
    return {'isLoggedIn': name != null, 'isAgreed': isAgreed};
  }
}
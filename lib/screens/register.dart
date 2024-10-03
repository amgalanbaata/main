import 'dart:math';
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:my_flutter_project/main.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;
import 'package:fluttertoast/fluttertoast.dart';
import 'bottonMenuPages/navigationBar.dart';

class Register extends StatefulWidget {
  const Register({Key? key}) : super(key: key);

  @override
  _RegisterState createState() => _RegisterState();
}

class _RegisterState extends State<Register> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isLoading = false;
  String errMessage = '';
  bool passwordIs = false;
  @override
  void initState() {
    super.initState();
  }

  Future<void> _saveUserData() async {
    // if (_formKey.currentState!.validate()) {
      try {
        SharedPreferences prefs = await SharedPreferences.getInstance();
        await prefs.setString('name', _nameController.text);
        await prefs.setString('number', _phoneController.text);

        print('Success: Амжилттай');
      } catch (e) {
        print('Error: Алдаа гарлаа');
      } finally {
        setState(() {
          _isLoading = false;
        });
      // }
    }
  }

  Future<bool> _checkUserEmail() async {
    final String email = _phoneController.text;
    final body = {
      "email": email,
    };
    try {
      final http.Response response = await http.post(
        Uri.parse('${apiUrl}api/email'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(body),
      );
      if (response.statusCode == 200) {
        
        print('OK');
        _showVerifyCodeDialog();
        return true;
      } else {
        print('Email not found');
        _navigateToHome();
        return false;
      }
    } catch (e) {
      setState(() {
        errMessage = 'error $e';
      });
      print('error');
      return false;
    }
  }

  Future<bool> _checkPassword() async {
    final String password = _passwordController.text;
    final String email = _phoneController.text;
    final body = {
      "password": password,
      "email": email
    };
    try {
      final http.Response response = await http.post(
        Uri.parse('${apiUrl}api/password'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(body),
      );
      print('successs');
      print(response.body);
      if (json.decode(response.body)['message'] == 'OK') {
        final data = jsonDecode(response.body);
        final String district = data['district'];
        final String committee = data['committee'];
        print('Response: $data');
        print('OK');
        SharedPreferences prefs = await SharedPreferences.getInstance();
        await prefs.setBool('committeeOfficer', true);
        await prefs.setString('district', district);
        await prefs.setString('committee', committee);
        _navigateToHome();
        return true;
      } else {
        print('passwrod is wrong');
        return false;
      }
    } catch (e) {
      setState(() {
        errMessage = 'error $e';
      });
      print('error');
      return false;
    }
  }
  
  Future<void> _showVerifyCodeDialog() async {
    final _formKey2 = GlobalKey<FormState>();
    await showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          // contentPadding: EdgeInsets.zero,
          titlePadding: EdgeInsets.zero,
          title: Container(
            width: double.infinity,
            padding: EdgeInsets.all(14),
            decoration: BoxDecoration(
              color: const Color.fromARGB(255, 11, 65, 38),
              borderRadius: BorderRadius.all(Radius.circular(40)),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.max,
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Text(
                  'Нууц үг',
                  style: 
                    TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                    color: Colors.white,
                    )
                ),
              ],
            ),
          ),
          content: Form(
            key: _formKey2,
            child: TextFormField(
              controller: _passwordController,
              keyboardType: TextInputType.text,
              maxLength: 20,
              decoration: InputDecoration(
                labelText: 'код',
                prefixIcon: Icon(Icons.sms, color: Color(0xFF2a7d2e),),
                border: OutlineInputBorder(),
                enabledBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8.0),
                  borderSide: BorderSide(
                    color: Color(0xFF2a7d2e)
                  ),
                ),
                focusedBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8.0),
                  borderSide: BorderSide(
                    color: Color(0xFF2a7d2e),
                    width: 2.0,
                  ),
                ),
                errorBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8.0),
                  borderSide: BorderSide(
                    color: Colors.red,
                  ),
                ),
                disabledBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8.0),
                  borderSide: BorderSide(
                    color: Colors.grey,
                  ),
                ),
                filled: true,
                fillColor: Colors.white,
              ),
              validator: (value) {
                if (value == null || value.isEmpty) {
                  return 'Нууц үгээ оруулна уу';
                } else {
                  return 'Буруу байна';
                }
                // return null;
              },
            ),
          ),
          actions: [
            TextButton(
              child: Text(
                'Цуцлах',
                style: TextStyle(color: Color(0xFF2a7d2e)),
                ),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
            ElevatedButton(
              onPressed: () async {
                await _checkPassword();
                if (_formKey2.currentState?.validate() ?? false) {
                  print('formkey $_passwordController.text');
                } else {
                  null;
                }
              },
              child: Text(
                'OK',
                style: TextStyle(color: Color(0xFF2a7d2e)),
                ),
            ),
          ],
        );
      },
    );
  }
  
  void _navigateToHome() async {
    Navigator.pop(context);
    await _saveUserData();
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => MainApp()),
    );
    print('call save user data function');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          mainAxisAlignment: MainAxisAlignment.start,
          children: [
            Container(
              margin: EdgeInsets.all(8.0),
              child: Image(
                height: 50,
                image: AssetImage('lib/assets/NBOG-logo.png'),
              ),
            ),
          ],
        ),
        bottom: PreferredSize(
          preferredSize: Size.fromHeight(2.0),
          child: Container(
            color: Color(0xFF2a7d2e),
            height: 2.0,
          ),
        ),
      ),
      body: _isLoading 
      ? Center(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              CircularProgressIndicator(),
              SizedBox(height: 8),
              Text('Loading...'),
            ],
          ),
        )
      : Padding(
        padding: const EdgeInsets.all(0),
        child: SingleChildScrollView(
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                Container(
                  width: double.maxFinite,
                  padding: EdgeInsets.all(10),
                  decoration: BoxDecoration(
                    color: const Color.fromARGB(255, 11, 65, 38),
                    borderRadius: BorderRadius.only(bottomLeft: Radius.circular(30), bottomRight: Radius.circular(30)),
                  ),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(
                        'Нэвтрэх',
                        style: 
                          TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                          )
                      ),
                    ],
                  ),
                ),
                SizedBox(height: 60),
                Padding(
                  padding:  EdgeInsets.all(16.0),
                  child: TextFormField(
                    controller: _nameController,
                    decoration: InputDecoration(
                      labelText: 'Нэр',
                      labelStyle: TextStyle(color: Color(0xFF1b5e20)),
                      prefixIcon: Icon(Icons.person, color: Color(0xFF2a7d2e),),
                      enabledBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8.0),
                        borderSide: BorderSide(
                          color: Color(0xFF2a7d2e)
                        ),
                      ),
                      focusedBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8.0),
                        borderSide: BorderSide(
                          color: Color(0xFF2a7d2e),
                          width: 2.0,
                        ),
                      ),
                      errorBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8.0),
                        borderSide: BorderSide(
                          color: Colors.red,
                        ),
                      ),
                      disabledBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8.0),
                        borderSide: BorderSide(
                          color: Colors.grey,
                        ),
                      ),
                      filled: true,
                      fillColor: Colors.white,
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Нэрээ оруулна уу';
                      }
                      return null;
                    },
                  ), 
                ),
                Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: TextFormField(
                    controller: _phoneController,
                    // maxLength: 8,
                    decoration: InputDecoration(
                      labelText: 'Дугаар, Имэйл',
                      labelStyle: TextStyle(color: Color(0xFF1b5e20)),
                      prefixIcon: Icon(Icons.phone, color: Color(0xFF2a7d2e),),
                      enabledBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8.0),
                        borderSide: BorderSide(
                          color: Color(0xFF2a7d2e),
                        ),
                      ),
                      focusedBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8.0),
                        borderSide: BorderSide(
                          color: Color(0xFF2a7d2e),
                          width: 2.0,
                        ),
                      ),
                      errorBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8.0),
                        borderSide: BorderSide(
                          color: Colors.red,
                        ),
                      ),
                      disabledBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8.0),
                        borderSide: BorderSide(
                          color: Colors.grey, 
                        ),
                      ),
                    ),
                    keyboardType: TextInputType.text,
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Утасны дугаараа оруулна уу';
                      }
                      if (!RegExp(r'^\d{8}$').hasMatch(value)) {
                        return 'Хүчинтэй 8 оронтой утасны дугаар оруулна уу';
                      }
                      return null;
                    },
                  ), 
                ),
                Container(
                  width: 200,
                  height: 45,
                  decoration: BoxDecoration(
                    gradient: LinearGradient(
                      colors: <Color>[
                        Color(0xFF2a7d2e),
                        Color(0xFF1b5e20),
                        Color(0xFF66bb6a),
                      ],
                    ),
                    borderRadius: BorderRadius.circular(8.0),
                  ),
                  child: TextButton(
                    child: Text(
                      'Нэвтрэх', 
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 15,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    onPressed: () {
                      setState(() {
                        _checkUserEmail();
                      });
                    },
                  ),
                )
              ],
            ),
          ),
        ),
      ),
    );
  }
}

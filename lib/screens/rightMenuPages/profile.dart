import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:ubsoil/main.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

import '../bottonMenuPages/navigationBar.dart';
class Profile extends StatefulWidget {
  const Profile({super.key});

  @override
  State<Profile> createState() => _ProfileState();
}

class _ProfileState extends State<Profile> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isEditing = false;
  bool _isLoading = false;
  int randomCode = 0;
  String errMessage = '';
  int? saveCode;
  String? keepNumber;
  bool passCorrect = false;
  String? _savedName;
  String? _savedNumber;
  String? userDistrict;
  String? userCommittee;
  bool isCommitteeOfficer = false;

  @override
  void initState() {
    super.initState();
    _loadUserData();
  }

  void _toastMessage(String title, String message) {
    Fluttertoast.showToast(
        msg: message,
        toastLength: Toast.LENGTH_SHORT,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIosWeb: 3,
        backgroundColor: Colors.red,
        textColor: Colors.white,
        fontSize: 16.0
    );
  }
  
  Future<void> _updateCommitteeOfficer(bool status) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    await prefs.setBool('committeeOfficer', status);

    setState(() {
      isCommitteeOfficer = status;
    });
    Navigator.pop(context, true);
  }

  Future<bool> _checkUserEmail() async {
    setState(() {
      _isLoading = true;
    });
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
        _showVerifyCodeDialog();
        return true;
      } else {
        _saveUserData();
        SharedPreferences prefs = await SharedPreferences.getInstance();
        await prefs.setBool('committeeOfficer', false);
        setState(() {
          committeeOfficer = false;
        });
        return false;
      }
    } catch (e) {
      setState(() {
        errMessage = 'error $e';
      });
      return false;
    } finally {
      setState(() {
        _isLoading = false  ;
      });
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

  
  Future<bool> _checkPassword() async {
    setState(() {
      _isLoading = true;
    });
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
        setState(() {
          committeeOfficer = true;
        });
        SharedPreferences prefs = await SharedPreferences.getInstance();
        await prefs.setBool('committeeOfficer', true);
        await _saveUserData();
        return true;
      } else {
        print('password is wrong');
        return false;
      }
    } catch (e) {
      setState(() {
        errMessage = 'error $e';
      });
      print('error');
      return false;
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  Future<void> _saveUserData() async {
    print('callSaveUserData');
    // if (_formKey.currentState!.validate()) {
      try {
        SharedPreferences prefs = await SharedPreferences.getInstance();
        await prefs.setString('name', _nameController.text);
        await prefs.setString('number', _phoneController.text);
        setState(() {
          _isEditing = false;
        });
        _showDialog('Success', 'Амжилттай');
      } catch (e) {
        _showDialog('Error', 'Алдаа гарлаа');
      } finally {
        setState(() {
          _isLoading = false;
        });
      }
    // }
  }

  Future<void> _loadUserData() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? name = prefs.getString('name');
    String? number = prefs.getString('number');
    String? district = prefs.getString('district');
    String? committee = prefs.getString('committee');
    setState(() {
      _nameController.text = name ?? '';
      _phoneController.text = number ?? '';
    });
    _savedName = name;
    _savedNumber = number;
    userDistrict = district;
    userCommittee = committee; 
    print(district);
    print(name);
  }

  void _toggleEdit() {
    setState(() {
      _isEditing = !_isEditing;
    });
  }

  void _showDialog(String title, String message) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: Text(title),
          content: Text(message),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
                Navigator.of(context).pop();
                Navigator.pop(context);
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => MainApp()),
                );
                _toastMessage('success', 'Амжилттай шинэчлэгдлээ');
              },
              child: Text('OK'),
            ),
          ],
        );
      },
    );
  }

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
        title: Text('Мэдээлэл шинэчлэх'),
        bottom: PreferredSize(
          preferredSize: Size.fromHeight(2.0),
          child: Container(
            color: Colors.green,
            height: 2.0,
          ),
        ),
        actions: [
          _isEditing
          ? IconButton(
              icon: Icon(Icons.check, color: Color(0xFF1b5e20),),
              onPressed: _toggleEdit,
            )
          : IconButton(
              icon: Icon(Icons.edit, color: Color(0xFF1b5e20),),
              onPressed: _toggleEdit,
            ),
        ],
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
      : 
      SingleChildScrollView(
          child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                TextFormField(
                  controller: _nameController,
                  enabled: _isEditing,
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
                    disabledBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(8.0),
                      borderSide: BorderSide(
                        color: Colors.grey,
                      ),
                    ),
                  ),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Нэрээ оруулна уу';
                    } 
                    // if (_nameController == _savedName && _phoneController == _savedNumber) {
                    //   return 'Өөрчлөлт байхгүй';
                    // }
                    return null;
                  },
                ),
                SizedBox(height: 20),
                TextFormField(
                  controller: _phoneController,
                  enabled: _isEditing,
                  decoration: InputDecoration(
                    labelText: 'Утас,иМэйл',
                    labelStyle: TextStyle(color: Color(0xFF1b5e20)),
                    prefixIcon: Icon(Icons.person, color: Color(0xFF2a7d2e),),
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
                      return 'Дугаараа оруулна уу';
                    } 
                    // if (_nameController == _savedName && _phoneController == _savedNumber) {
                    //   return 'Өөрчлөлт байхгүй';
                    // }
                    return null;
                  },
                ),
                SizedBox(height: 30),
                if (_isEditing)
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
                    borderRadius: BorderRadius.circular(32.0),
                  ),
                  child: TextButton(
                    child: Text(
                      'Шинэчлэх', 
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 15,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    onPressed: () {
                      if (_nameController.text == _savedName && _phoneController.text == _savedNumber) {
                        _toastMessage('error', 'Өөрчлөлт байхгүй байна');
                      } else {
                        print(_nameController.text);
                        print(_phoneController.text);
                        print(_savedName);
                        print(_savedNumber);
                        setState(() {
                          _checkUserEmail();
                        });
                      }
                    },
                  ),
                ),
                committeeOfficer
                ? Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Padding(
                        padding: const EdgeInsets.symmetric(vertical: 16.0),
                        child: Text(
                          'Бүртгэлийн мэдээлэл',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                            color: Color(0xFF1b5e20),
                          ),
                        ),
                      ),
                      Container(
                        padding: const EdgeInsets.all(16.0),
                        decoration: BoxDecoration(
                          color: Colors.grey[100],
                          borderRadius: BorderRadius.circular(12.0),
                          border: Border.all(
                            color: Color(0xFF2a7d2e),
                            width: 1.5,
                          ),
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              children: [
                                Icon(Icons.location_city, color: Color(0xFF2a7d2e)),
                                SizedBox(width: 8),
                                Text(
                                  'Дүүрэг:',
                                  style: TextStyle(
                                    fontSize: 16,
                                    fontWeight: FontWeight.bold,
                                    color: Color(0xFF2a7d2e),
                                  ),
                                ),
                                Text(
                                  '$userDistrict',
                                  style: TextStyle(
                                    fontSize: 16,
                                    color: Colors.black87,
                                  ),
                                ),
                              ],
                            ),
                            SizedBox(height: 16),
                            Row(
                              children: [
                                Icon(Icons.account_balance, color: Color(0xFF2a7d2e)),
                                SizedBox(width: 8),
                                Text(
                                  'Хороо: ',
                                  style: TextStyle(
                                    fontSize: 16,
                                    fontWeight: FontWeight.bold,
                                    color: Color(0xFF2a7d2e),
                                  ),
                                ),
                                Text(
                                  '$userCommittee',  // Replace with actual committee data
                                  style: TextStyle(
                                    fontSize: 16,
                                    color: Colors.black87,
                                  ),
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ],
                  )
                : Column(),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

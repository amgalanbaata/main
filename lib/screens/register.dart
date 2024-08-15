import 'dart:math';
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:my_flutter_project/main.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;
import 'package:restart_app/restart_app.dart';
import 'package:fluttertoast/fluttertoast.dart';

class Register extends StatefulWidget {
  const Register({Key? key}) : super(key: key);

  @override
  _RegisterState createState() => _RegisterState();
}

class _RegisterState extends State<Register> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _codeController = TextEditingController();
  bool _isLoading = false;
  int randomCode = 0;
  String errMessage = ''; 
  // String? _response = '';
  int? _savedCode;
  String? _previousPhoneNumber; 
  final String url = 'http://192.168.50.243:8000/api/';

  @override
  void initState() {
    super.initState();
    _loadSavedCode();
  }

  Future<void> _register() async {
    setState(() {
      _isLoading = true;
    });
    if (_formKey.currentState?.validate() ?? false) {
      String name = _nameController.text;
      String number = _phoneController.text;

      if (_previousPhoneNumber == null || _previousPhoneNumber != number) {
        randomCode = createRandomCode();
        print(randomCode);
        bool res = await sendCode();
        if(res) {
          await _storeUserInformation(name, number, randomCode);
          _previousPhoneNumber = number;
          print('Code sent to: $number');
          await _showVerifyCodeDialog();
        } else {
          Fluttertoast.showToast(
            msg: "Код илгээхэд алдаа гарлаа. Дахин оролдоно уу.",
            toastLength: Toast.LENGTH_SHORT,
            gravity: ToastGravity.BOTTOM,
            timeInSecForIosWeb: 1,
            backgroundColor: Colors.red,
            textColor: Colors.white,
            fontSize: 16.0,
          );
        }        
      } else {
        Fluttertoast.showToast(
          msg: "Энэ дугаар руу код илгээсэн байна.",
          toastLength: Toast.LENGTH_SHORT,
          gravity: ToastGravity.BOTTOM,
          timeInSecForIosWeb: 1,
          backgroundColor: Colors.red,
          textColor: Colors.white,
          fontSize: 16.0,
        );
        await _showVerifyCodeDialog();
        print('Code already sent to this number');
        print(randomCode);
      }
    } else {
      print('Алдаа гарлаа дахин оролдоно уу !!!');
    }
    setState(() {
      _isLoading = !_isLoading;
    });
  }

  Future<void> _storeUserInformation(String name, String number, int randomCode) async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    // await prefs.setString('name', name);
    await prefs.setString('number', number);
    await prefs.setInt('code', randomCode);
  }

  Future<bool> sendCode() async {
    final String phone = _phoneController.text;
    final int code = randomCode;
    final body = {
      "phone": phone,
      "code": code,
    };
    try {
      final http.Response response = await http.post(
        Uri.parse('${apiUrl}sendCode'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(body),
      );
      print('success');
      print(response.body);
      if (json.decode(response.body)['message'] == 'OK') {
        return true;
      } else {
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

  int createRandomCode() {
    Random random = Random();
    int min = 1000;
    int max = 9999;
    return min + random.nextInt(max - min + 1);
  }

  void _navigateToHome() {
    Restart.restartApp();
  }

  Future<void> _loadSavedCode() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
      _savedCode = prefs.getInt('code');
      _previousPhoneNumber = prefs.getString('number');
    });
  }

  Future<void> _verifyCode() async {
    setState(() {
      _isLoading = true;
    });
    await _loadSavedCode();
    if (_savedCode != null && _savedCode.toString() == _codeController.text) {
      String name = _nameController.text;
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      await prefs.setString('name', name);
      _navigateToHome();
      print('successfull');
    } else {
      print('SMS кодоо оруулна уу');
    }
    setState(() {
      _isLoading = false;
    });
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
              // borderRadius: BorderRadius.only(topLeft: Radius.circular(30), topRight: Radius.circular(30)),
              borderRadius: BorderRadius.all(Radius.circular(40)),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.max,
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Text(
                  'Баталгаажуулах код',
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
              controller: _codeController,
              keyboardType: TextInputType.phone,
              maxLength: 4,
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
                  return 'SMS кодоо оруулна уу';
                } else if (_savedCode.toString() != value) {
                  return 'Буруу код байна';
                }
                return null;
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
                await _loadSavedCode();
                if (_formKey2.currentState?.validate() ?? false) {
                  _verifyCode();
                  // Navigator.of(context).pop();
                } else {
                  print('aldaa garlaa');
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

  @override
  void dispose() {
    _nameController.dispose();
    _phoneController.dispose();
    _codeController.dispose();
    super.dispose();
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
                        'Бүртгүүлэх',
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
                    maxLength: 8,
                    decoration: InputDecoration(
                      labelText: 'Дугаар',
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
                          color: Colors.red, // Border color when there's an error
                        ),
                      ),
                      disabledBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8.0),
                        borderSide: BorderSide(
                          color: Colors.grey, // Border color when the field is disabled
                        ),
                      ),
                    ),
                    keyboardType: TextInputType.phone,
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
                      'Бүртгүүлэх', 
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 15,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    onPressed: () {
                      setState(() {
                        _register();
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

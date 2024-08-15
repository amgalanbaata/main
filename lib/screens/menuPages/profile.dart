import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:my_flutter_project/main.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'dart:math';
class Profile extends StatefulWidget {
  const Profile({super.key});

  @override
  State<Profile> createState() => _ProfileState();
}

class _ProfileState extends State<Profile> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _codeController = TextEditingController();
  bool _isEditing = false;
  bool _isLoading = false;
  int randomCode = 0;
  String errMessage = '';
  String? _previousPhoneNumber;
  String? _previousUserName;
  int? _savedCode;
  int? saveCode;
  String? keepNumber;

  @override
  void initState() {
    super.initState();
    _loadUserData();
    _loadSavedCode();
  }

    Future<void> _checkNumber() async {
    if (_formKey.currentState?.validate() ?? false) {
      setState(() {
        _isEditing = true;
      });
      print(_savedCode);
      print('random code $randomCode');
      String name = _nameController.text;
      String number = _phoneController.text;
      if(_previousUserName != name && _previousPhoneNumber == number) {
        print('zowhon ner oorchlogdson nohtsold');
        try {
          final SharedPreferences prefs = await SharedPreferences.getInstance();
          await prefs.setString('name', name,);
          setState(() {
            _previousUserName = prefs.getString('name');
          });
          print('successfull');
          _showDialog('Амжилттай', 'Шинэчилэгдлээ'); 
        } catch (e) {
        Fluttertoast.showToast(
          msg: "Амжилтгүй', 'Ахин оролдоно уу!",
          toastLength: Toast.LENGTH_SHORT,
          gravity: ToastGravity.BOTTOM,
          timeInSecForIosWeb: 1,
          backgroundColor: Colors.red,
          textColor: Colors.white,
          fontSize: 16.0,
        );
        } finally {
          setState(() {
            _isLoading = false;
          });
        }
      } else if(_previousPhoneNumber != number && keepNumber != number) {
          keepNumber = number;
          randomCode = createRandomCode();
          await _storeUserInformation(name, number, randomCode);
          await _sendCode();
          print(randomCode);
          print(number);
          await _showVerifyCodeDialog();
          // _previousPhoneNumber = number;
          _savedCode = randomCode;
        } else if (_previousPhoneNumber != number && saveCode == randomCode){
          print(_savedCode);
          print(randomCode);
          Fluttertoast.showToast(
            msg: "${number} Дугаарлуу код илгээсэн байна",
            toastLength: Toast.LENGTH_SHORT,
            gravity: ToastGravity.BOTTOM,
            timeInSecForIosWeb: 1,
            backgroundColor: Colors.red,
            textColor: Colors.white,
            fontSize: 16.0,
          );
          await _showVerifyCodeDialog();
        } 
        else if (_previousPhoneNumber == number && _previousUserName == name && _savedCode != null){
          Fluttertoast.showToast(
            msg: "Өөрчлөлт байхгүй",
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
        msg: "Алдаа гарлаа дахин оролдоно уу !!!",
        toastLength: Toast.LENGTH_SHORT,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIosWeb: 1,
        backgroundColor: Colors.red,
        textColor: Colors.white,
        fontSize: 16.0,
      );
    }
    setState(() {
      _isLoading = false;
    });
  }

  Future<void> _storeUserInformation(String name, String number, int randomCode) async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    // await prefs.setString('name', name);
    // await prefs.setString('number', number);
    await prefs.setInt('code', randomCode);
  }

  Future<bool> _sendCode() async {
    final String phone = _phoneController.text;
    final int code = randomCode;
    saveCode = code;
    final body = {
      "phone": phone,
      "code": code,
    };
    try{
      final http.Response response = await http.post(
        Uri.parse('${apiUrl}sendCode'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(body),
      );
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

  Future<void> _loadSavedCode() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
      _savedCode = prefs.getInt('code');
      _previousPhoneNumber = prefs.getString('number');
      _previousUserName = prefs.getString('name');
    });
  }

  Future<void> _saveUserData() async {
    if (_formKey.currentState!.validate()) {
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
    }
  }

  Future<void> _loadUserData() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? name = prefs.getString('name');
    String? number = prefs.getString('number');
    setState(() {
      _nameController.text = name ?? '';
      _phoneController.text = number ?? '';
    });
  }

  Future<void> _verifyCode() async {
    setState(() {
      _isLoading = true;
    });
    await _loadSavedCode();
    // _saveUserData();
    if (_savedCode != null && _savedCode.toString() == _codeController.text) {
      try {
        _saveUserData();
        String name = _nameController.text;
        String number = _phoneController.text;
        final SharedPreferences prefs = await SharedPreferences.getInstance();
        await prefs.setString('name', name,);
        await prefs.setString('number', number);
        print('successfull');
        _showDialog('Амжилттай', 'Шинэчилэгдлээ'); 
      } catch (e) {
        _showDialog('Алдаа гарлаа', 'Ахин оролдоно уу!');
      }finally {
        setState(() {
          _isLoading = false;
        });
      }
    } 
  }

    Future<void> _showVerifyCodeDialog() async {
    final _formKey2 = GlobalKey<FormState>();
    await showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
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
                  Navigator.of(context).pop();
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
                // Navigator.push(
                //   context,
                //   MaterialPageRoute(builder: (context) => Home()),
                // );
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
      : Padding(
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
                    return 'Please enter your name';
                  }
                  return null;
                },
              ),
              SizedBox(height: 20),
              TextFormField(
                controller: _phoneController,
                enabled: _isEditing,
                decoration: InputDecoration(
                  labelText: 'Утас',
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
                    return 'Please enter your phone number';
                  }
                  if (!RegExp(r'^\d{8}$').hasMatch(value)) {
                    return 'Please enter a valid 8-digit phone number';
                  }
                  return null;
                },
              ),
              SizedBox(height: 30),
              // if (_isEditing)
              // ElevatedButton(
              //   onPressed: _checkNumber,
              //   child: Text('Шинэчлэх'),
              // ),
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
                    setState(() {
                      _checkNumber();
                    });
                  },
                ),
              )
            ],
          ),
        ),
      ),
    );
  }
}

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

  Future<void> _storeUserInformation(String name, String number, int randomCode) async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
  }


  Future<void> _loadSavedCode() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
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
                      _verifyCode();
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

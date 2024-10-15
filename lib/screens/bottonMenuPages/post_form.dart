import 'dart:convert';
import 'dart:io';

import 'package:dropdown_button2/dropdown_button2.dart';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:geolocator/geolocator.dart';
import 'package:ubsoil/main.dart';
import 'package:ubsoil/screens/bottonMenuPages/navigationBar.dart';
import 'package:ubsoil/screens/menu.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:image/image.dart' as img;
import 'package:intl/intl.dart';


import '../../database.dart';

class PostForm extends StatefulWidget {
  const PostForm({Key? key}) : super(key: key);

  @override
  State<PostForm> createState() => _PostFormState();
}

class _PostFormState extends State<PostForm> {
  String _response = '';
  final _sqliteService = DatabaseHelper.instance;
  final GlobalKey<FormState> _formkey = GlobalKey<FormState>();
  final TextEditingController _comment = TextEditingController();
  bool _isLoading = false;
  List<File> _imageFile = [];
  final _picker = ImagePicker();
  String location = 'hooson';
  String? _name;
  String? _number;
  String formattedDate = DateFormat('yyy-MM-dd').format(DateTime.now());
  int selectedValue = 1;

  @override
  void initState() {
    super.initState();
    _fetchUserData();
    fetchTypeName();
  }

  Future<void> fetchTypeName() async {
    try {
      final response = await http.post(Uri.parse('${apiUrl}api/typeName'));
      print('Response status: ${response.statusCode}');
      print('Response body: ${response.body}');
      if (response.statusCode == 200) {
        final List<dynamic> data = jsonDecode(response.body);
        setState(() {
          typeNameGlobal = data.toList();
          print('items:');
          print(typeNameGlobal);
        });
      } else {
        print('Failed to load type names');
      }
    } catch (e) {
      print('Error: $e');
    }
  }

  Future<void> _fetchUserData() async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
      _name = prefs.getString('name');
      _number = prefs.getString('number');
    });
  }

  Future<void> getImageFromCamera() async {
  if (_imageFile.length >= 3) {
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(
      content: Text('Та зөвхөн 3 хүртэлх зураг байршуулах боломжтой.'),
    ));
    return;
  }

  PermissionStatus status = await Permission.location.request();
  print("status.isGranted: ");
  print(status.isGranted);

  if (status.isGranted) {
    print(status.isGranted);
    final pickedFile = await _picker.pickImage(source: ImageSource.camera);
    if (pickedFile != null) {
      File originalFile = File(pickedFile.path);
      File resizedFile = await resizeImage(originalFile);

      setState(() {
        _imageFile.add(resizedFile);
      });
    }
  } else if (!status.isDenied || status.isPermanentlyDenied) {
    print(status.isDenied);
    print('call checkLocationService');
    // it's here again location ask
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(
      content: Text('Камерт хандахын тулд байршлын зөвшөөрөл шаардлагатай.'),
    ));
    Future.delayed(const Duration(seconds: 3)
    );
    openAppSettings();
  }
}

// Function to resize the image
Future<File> resizeImage(File file) async {
  // Read the image file into memory
  final bytes = await file.readAsBytes();
  final img.Image? image = img.decodeImage(bytes);

  if (image != null) {
    // Resize the image to a fixed width (e.g., 400px), keeping aspect ratio
    img.Image resizedImage = img.copyResize(image, width: 500);

    // Encode the resized image back to file
    final resizedBytes = img.encodeJpg(resizedImage);
    final resizedFile = File(file.path)..writeAsBytesSync(resizedBytes);

    return resizedFile;
  } else {
    throw Exception("Failed to decode image");
  }
}


  // Future<void> getImageFromCamera() async {
  //   if(_imageFile.length >= 3) {
  //     ScaffoldMessenger.of(context).showSnackBar(SnackBar(
  //       content: Text('Та зөвхөн 3 хүртэлх зураг байршуулах боломжтой.'),
  //     ));
  //     return;
  //   }
  //   PermissionStatus status = await Permission.location.request();

  //   if (status.isGranted) {
  //     final pickedFile = await _picker.pickImage(source: ImageSource.camera);
  //     if (pickedFile != null) {
  //       setState(() {
  //         _imageFile.add(File(pickedFile.path));
  //       });
  //     }
  //   } else if (status.isDenied || status.isPermanentlyDenied) {
  //     ScaffoldMessenger.of(context).showSnackBar(SnackBar(
  //       content: Text('Камерт хандахын тулд байршлын зөвшөөрөл шаардлагатай.'),
  //     ));
  //   }
  // }

  Future<void> getImageFromGallery() async {
    final pickedFiles = await _picker.pickMultiImage();
    if (pickedFiles != null) {
      setState(() {
        _imageFile.addAll(pickedFiles.map((pickedFile) => File(pickedFile.path)));
      });
    }
  }

  Future<String> fileToBase64String(File file) async {
    List<int> fileBytes = await file.readAsBytes();
    String base64String = base64Encode(fileBytes);
    return base64String;
  }
  
  Future<List<String>> filesToBase64Strings(List<File> files) async {
    List<String> base64Strings = [];
    
    for (File file in files) {
      String base64String = await fileToBase64String(file);
      base64Strings.add(base64String);
    }

    return base64Strings;
  }

  Future<void> _createPost() async {
    if (_imageFile.isEmpty || _comment.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Зураг болон тайлбар хэсгийг бөглөнө үү.'),
        )
      );
      return;
    } 
    
    PermissionStatus status = await Permission.location.request();

    String latitude = '';
    String longitude = '';
    List<String> image = await filesToBase64Strings(_imageFile);
    if (status.isGranted) {
      Position position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
      );
      latitude = position.latitude.toString();
      longitude = position.longitude.toString();
    } else {
      print('medegdel inlgeehiin tuld location shaardlagatai');
      return;
    }
    setState(() {
      _isLoading = true;
    });
    final Map<String, dynamic> body = {
      'name': _name,
      'number': _number,
      'comment': _comment.text,
      'image': image,
      'latitude': latitude,
      'longitude': longitude,
      'type': selectedValue,
    };
    print('response.body');
    print(body);

    try {
      final http.Response response = await http.post(
        Uri.parse('${apiUrl}api/posts'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8'
        },
        body: jsonEncode(body),
      );

      if (response == null) {
        Navigator.of(context).pop();
        print(response);
        print('check response');
      }

      if (response.statusCode == 200) {
        setState(() {
          print(response.body);
          final responseBody = json.decode(response.body);
          print(responseBody['id'].toString());
          print(responseBody['post']['status'].toString());
          final Map<String, dynamic> row = {
            'id': responseBody['id'],
            'name': _name,
            'number': _number,
            'comment': _comment.text,
            'type': selectedValue,
            'status': 1,
            'image1': _imageFile.length > 0 ? _imageFile[0].path : null,
            'image2': _imageFile.length > 1 ? _imageFile[1].path : null,
            'image3': _imageFile.length > 2 ? _imageFile[2].path : null,
            'latitude': latitude,
            'longitude': longitude,
            'date': formattedDate,
          };

          _sqliteService.insertSendPost(row);
          // print(DateTime.now().toString());
          _showDialog('Амжилттай илгээлээ', '.....');
        });
      } else {
      setState(() {
      //   final Map<String, dynamic> row = {
      //     'name': _name,
      //     'number': _number,
      //     'comment': _comment.text,
      //     'image1': _imageFile.length > 0 ? _imageFile[0].path : null,
      //     'image2': _imageFile.length > 1 ? _imageFile[1].path : null,
      //     'image3': _imageFile.length > 2 ? _imageFile[2].path : null,
      //     'status': 0,
      //     'type': selectedValue,
      //     'latitude': latitude,
      //     'longitude': longitude,
      //     'date': formattedDate,
      //   };
      //   _sqliteService.insertPost(row);
        _response = 'Failed: ${response.statusCode}';
        print('Failed response: ${response.body}'); // Added response body here
        print(response.statusCode);
          _showDialog('Амжилтгүй', 'дахин оролдоно уу');
      });
    }
  } catch (e) {
    setState(() {
      final Map<String, dynamic> row = {
        'name': _name,
        'number': _number,
        'comment': _comment.text,
        'image1': _imageFile.length > 0 ? _imageFile[0].path : null,
        'image2': _imageFile.length > 1 ? _imageFile[1].path : null,
        'image3': _imageFile.length > 2 ? _imageFile[2].path : null,
        'status': 0,
        'type': selectedValue,
        'latitude': latitude,
        'longitude': longitude,
        'date': formattedDate,
      };
      _showDialog('интернэт холболт байхгүй', 'Мэдээлэл хадгалагдлаа, интернэттэй газраас ахин илгээнэ үү!!!');
      _sqliteService.insertPost(row);
    });
  }
  setState(() {
    _isLoading = !_isLoading;
  });
}

  void _showDialog(String title, String _showDialog) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text(title),
          content: Text(_showDialog),
          actions: <Widget>[
            TextButton(
              child: Text('OK'),
              onPressed: () {
                if(title == 'Амжилтгүй') {
                  Navigator.of(context).pop();
                } else {
                  Navigator.pushAndRemoveUntil(
                    context, 
                    MaterialPageRoute(builder: (context) => MainApp()),
                    (Route<dynamic> route) => false,
                  );
                }
              },
            ),
          ],
        );
      },
    );
  }

  // final List<String> items = ['Бусад', 'Хог хягдал', 'эвдрэл доройтол', 'Бохир'];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      endDrawer: Menu(),
      appBar: AppBar(
        title: Row(
          mainAxisAlignment: MainAxisAlignment.start,
          children: [
            Container(
              margin: EdgeInsets.all(8.0),
              child: Image(
                height: 50,
                // image: NetworkImage('https://environment.ub.gov.mn/assets/images/resources/NBOG-logo.png'),
                image: AssetImage('lib/assets/NBOG-logo.png'),
              ),
            ),
          ],
        ),
        bottom: PreferredSize(
          preferredSize: Size.fromHeight(2.0),
          child: Container(
            color: Colors.green,
            height: 2.0,
          ),
        ),
      ),
      body: _isLoading
      ? Center(child: CircularProgressIndicator())
      : ListView(
          children: [
            Container(
              width: MediaQuery.of(context).size.width,
              height: _imageFile.isEmpty ? 20 : 200,
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                itemCount: _imageFile.length,
                itemBuilder: (context, index) {
                  File? file = _imageFile[index];
                  if (file == null) {
                    return Container();
                  }
                  return Stack(
                    children: [
                      Padding(
                        padding: const EdgeInsets.all(8.0),
                        child: Image.file(file),
                      ),
                      Positioned(
                        right: 0,
                        child: IconButton(
                          icon: Icon(Icons.remove_circle, color: Colors.red),
                          onPressed: () {
                            setState(() {
                              _imageFile.removeAt(index);
                            });
                          }
                        ),
                      )
                    ],
                  );
                },
              ),
            ),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                ElevatedButton.icon(
                  onPressed: getImageFromCamera,
                  icon: Icon(Icons.camera),
                  label: Text('Камер нээх'),
                  style: ElevatedButton.styleFrom(),
                ),
              ],
            ),
            SizedBox(height: 10),
            Padding(
              padding: const EdgeInsets.all(8.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Төрөл сонгох',
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  SizedBox(height: 8),
                  Container(
                    decoration: BoxDecoration(
                      border: Border.all(color: Colors.black38, width: 1),
                      borderRadius: BorderRadius.circular(4),
                    ),
                    child: DropdownButtonHideUnderline(
                      child: DropdownButton2<String>(
                        isExpanded: true,
                        hint: const Row(
                          children: [
                            Icon(
                              Icons.list,
                              size: 16,
                              color: Colors.green,
                            ),
                            SizedBox(width: 4),
                            Expanded(
                              child: Text(
                                'Сонгох',
                                style: TextStyle(
                                  fontSize: 14,
                                  fontWeight: FontWeight.bold,
                                  color: Colors.green,
                                ),
                                overflow: TextOverflow.ellipsis,
                              ),
                            ),
                          ],
                        ),
                        items: typeNameGlobal
                            .map((item) => DropdownMenuItem<String>(
                                  value: item['id'].toString(),
                                  child: Text(
                                    item['name'],
                                    style: const TextStyle(fontSize: 14),
                                  ),
                                ))
                            .toList(),
                        value: selectedValue.toString(),
                        onChanged: (String? value) {
                          setState(() {
                            selectedValue = int.parse(value!);
                            print(selectedValue);
                          });
                        },
                        buttonStyleData: const ButtonStyleData(
                          padding: EdgeInsets.symmetric(horizontal: 16),
                          height: 40,
                          width: double.infinity,
                        ),
                        menuItemStyleData: const MenuItemStyleData(
                          height: 40,
                        ),
                      ),
                    ),
                  ),
                  SizedBox(height: 16),
                  Text(
                    'Тайлбар',
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  SizedBox(height: 8),
                  Container(
                    decoration: BoxDecoration(
                      border: Border.all(color: Colors.black38, width: 1),
                      borderRadius: BorderRadius.circular(4),
                    ),
                    child: TextFormField(
                      controller: _comment,
                      keyboardType: TextInputType.multiline,
                      maxLines: 9,
                      validator: (val) =>
                          val!.isEmpty ? 'Тайлбар хэсэг шаардлагатай' : null,
                      decoration: InputDecoration(
                        hintText: 'Тайлбар',
                        border: InputBorder.none,
                        contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
        floatingActionButton: Container(
          decoration: BoxDecoration(
            gradient: LinearGradient(
              colors: <Color>[
                Color(0xFF2a7d2e),
                Color(0xFF1b5e20),
                Color(0xFF66bb6a),
              ]
            ),
            borderRadius: BorderRadius.circular(8.0),
          ),
          margin: EdgeInsets.only(bottom: 0, left: 15),
          child: FloatingActionButton.extended(
            onPressed: () {
              _createPost();
            },
            icon: Icon(Icons.send, color: Colors.white,),
            label: Text(
              'Мэдэгдэл илгээх',
              style: TextStyle(color: Colors.white),
            ),
            backgroundColor: Colors.transparent,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(8),
            ),
          ),
        ),
        floatingActionButtonLocation: FloatingActionButtonLocation.centerFloat,
      );
    }
  }
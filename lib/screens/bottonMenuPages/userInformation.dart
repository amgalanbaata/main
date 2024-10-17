import 'dart:convert';
import 'dart:io';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:ubsoil/main.dart';
import 'package:ubsoil/screens/menu.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
// import '../database.dart';
import 'package:ubsoil/database.dart';
// import 'constant/data.dart';
import 'package:ubsoil/screens/constant/data.dart';
import 'dart:math';


class Userinformation extends StatefulWidget {
  const Userinformation({super.key});

  @override
  State<Userinformation> createState() => _UserinformationState();
}

GlobalKey _scaffoldGlobalKey = GlobalKey();
class _UserinformationState extends State<Userinformation> with SingleTickerProviderStateMixin {
  final _sqliteService = DatabaseHelper.instance;
  int currentIndex = 0;
  List<dynamic> data = [];
  RefreshController  _refreshController  = RefreshController(initialRefresh: false);
  bool isDetailsClicked = false;
  late AnimationController _controller;
  late Animation<Color?> _colorAnimation;
  bool showItem = true;
  bool isSelectButton = true;

  String message = 'Амжилттай илгээлээ';

  String errMessage = '';
  int allPostsCount = 0;
  int sendPostsCount = 0;
  int unsendPostsCount = 0;

  @override
  initState() {
    super.initState();
    data.clear();
    typeNameGlobal = [];
    statusNameGlobal = [];
    fetchStatus();
    allPostCount();
    fetchTypeName();
    fetchStatusName();
    getCounts();    
    getpost();
    getSentPosts();
    _controller = AnimationController(
      duration: const Duration(seconds: 1),
      vsync: this,
    )..repeat(reverse: true);

    _colorAnimation = ColorTween(
      begin: Colors.red,
      end: Colors.transparent,
    ).animate(_controller);
  }

  
  Future<void> fetchTypeName() async {
    try {
      final response = await http.post(Uri.parse('${apiUrl}api/typeName'));
      if (response.statusCode == 200) {
        final List<dynamic> data = jsonDecode(response.body);
        setState(() {
          typeNameGlobal = data.toList();
        });
      } else {
        print('Failed to load type names');
      }
    } catch (e) {
      print('Error: $e');
    }
  }

  Future<void> getSentPosts() async {
    print('call getUserPosts');
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? number = prefs.getString('number');
    if (number == null || number.isEmpty) {
      print('No number found in SharedPreferences');
      return;
    }
    String userEmail = number;
    showLoader(_scaffoldGlobalKey);
    try {
      final response = await http.post(Uri.parse('${apiUrl}api/get-sent-posts?email=${userEmail}'));
      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        hideLoader(_scaffoldGlobalKey);
        setState(() {
          List<dynamic> posts = jsonResponse['posts'];
          data.addAll(jsonResponse['posts']);
          sendPostsCount = posts.length;
          print(response.statusCode);
          print(posts);
          print(data.length);
        });        
      } else {
        hideLoader(_scaffoldGlobalKey);
        _showDialog('Алдаа', 'Хэрэглэгчийн мэдээллийг уншиж чадсангүй');
        print('Failed to load posts');
      }
    } catch (e) {
      hideLoader(_scaffoldGlobalKey);
      _showDialog('Алдаа', 'Хэрэглэгчийн мэдээллийг уншиж чадсангүй');
      print('Error: $e');
    }
    // hideLoader(_scaffoldGlobalKey);
  }
  
  Future<void> fetchStatusName() async {
    try {
      final response = await http.post(Uri.parse('${apiUrl}api/statusName'));
      if (response.statusCode == 200) {
        final List<dynamic> data = jsonDecode(response.body);
        setState(() {
          statusNameGlobal = data.toList();
        });
      } else {
        print('Failed to load status names');
      }
    } catch (e) {
      print('Error: $e');
    }
  }

  Future<void> getCounts() async{
    DatabaseHelper dbHelper = DatabaseHelper.instance;
    int sendPosts = await dbHelper.getSendPostsCount();
    int posts = await dbHelper.getUnsendPostsCount();

    setState(() {
      sendPostsCount = sendPosts;
      unsendPostsCount = posts;
    });
  }

  Future<void> getpost() async {
    
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      await _sqliteService.getPosts().then((res) {
        setState(() {
          data.addAll(res);
          print(res);
          print(data.length);
        });
      });
      // await _sqliteService.getSendPosts().then((res) {
      //   setState(() {
      //     data.addAll(res);
      //   });
      //   print('getePostsRes: ');
      //   print(res);
      // });
    });
  }

  Future<void> _onRefresh() async {
    fetchStatus();
    _refreshController.refreshCompleted();
    allPostCount();
    data.clear();
    getCounts();    
    getpost();
    getSentPosts();
    print('working scroll...');
    typeNameGlobal.forEach((item) {
      print(item['name']);
    });
  }

  Future<void> _onLoading() async {
    fetchStatus();
    _refreshController.loadComplete();
  }

  Future<void> deletepost(id) async {
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      print('delete test');
      await _sqliteService.deletePost(id).then((res) {
        setState(() {
          // getpost();
          _onRefresh();
          print('deleted post');
        });
      });
    });
  }

  Future<void> send(Map<String, dynamic> item) async {
    print('item: ');
    print(item);
    showLoader(_scaffoldGlobalKey);
    try {
      print('item');
      print(item);
      List<String> image = [];
      String formattedDate = DateFormat('yyy-MM-dd').format(DateTime.now());
      if (item['image1'] != null) {
        File file1 = File(item['image1']);
        List<int> fileBytes1 = await file1.readAsBytes();
        String base64Str1 = base64Encode(fileBytes1);
        image.add(base64Str1);
      }
      if (item['image2'] != null) {
        File file2 = File(item['image2']);
        List<int> fileBytes2 = await file2.readAsBytes();
        String base64Str2 = base64Encode(fileBytes2);
        image.add(base64Str2);
      }
      if (item['image3'] != null) {
        File file3 = File(item['image3']);
        List<int> fileBytes3 = await file3.readAsBytes();
        String base64Str3 = base64Encode(fileBytes3);
        image.add(base64Str3);
      }

      final Map<String, dynamic> body = {
        'name': item['name'],
        'number': item['number'],
        'comment': item['comment'],
        'type': item['type'],
        'image': image,
        'latitude': item['latitude'],
        'longitude': item['longitude'],
      };
      final http.Response response = await http.post(
        Uri.parse('${apiUrl}api/posts'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8'
        },
        body: jsonEncode(body),
      );
      hideLoader(_scaffoldGlobalKey);
      if (response.statusCode == 200) {
        _showDialog(message,'Мэдээллээ амжилттай илгээлээ.');
        deletepost(item['id']);
        // _onRefresh();
      } else {
        setState(() {
          _showMessage('Илгээж чадсангүй: Та дахин оролдоно уу...');
          print('failed response');
          print(response.statusCode);
        });
      }
    } catch (e) {
      hideLoader(_scaffoldGlobalKey);
      setState(() {
          _showMessage('Алдаа: Интернэт холболт байхгүй байна, интернэттэй газраас дахин илгээнэ үү!!!');
      });
    }    
  }

  Future<void> allPostCount() async {
    try {
    final http.Response response = await http.post(
    Uri.parse('${apiUrl}api/count'),
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
      },
    );
    if (response.statusCode == 200) {
      final Map<String, dynamic> responceData = jsonDecode(response.body);
      setState(() {
        allPostsCount = responceData['all'];
      });
    }
    } 
    catch (e) {
      setState(() {
        errMessage = 'Error: $e';
      });
    }
  }

  Future<void> fetchStatus() async {
      List<int> allID = [];
      await _sqliteService.getSendPosts().then((res) {
          for (var i in res) {
            allID.add(i['id']);
          }
      });
      final Map<String, dynamic> body = {
        'id': allID
      };
      final http.Response response = await http.post(
        Uri.parse('${apiUrl}api/myposts'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8'
        },
        body: jsonEncode(body),
      );
      setState(() {
        if(response.statusCode == 200) {
          final List<dynamic> responseData = jsonDecode(response.body);
            for (var item in responseData) {
              final Map<String, dynamic> row = {
                'status': item['status'],
                'admin_comment': item['admin_comment'],
              };
              int postId = int.parse(item['id'].toString());
              _sqliteService.updatePostStatus(row, postId);
            }
            // getpost();
        }
      });
  }

  void _showMessage(String message) {
    setState(() {
      message = message;
    });
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        duration: Duration(seconds: 3),
      ),
    );
  }

  void _showDialog(String title, String message) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text(title),
          content: Text(message),
          actions: <Widget>[
            TextButton(
              child: Text('OK'),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
          ],
        );
      },
    );
  }

  void _showImageDialog(String imagePath) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return Dialog(
          child: Container(
            child: !imagePath.contains('http') ? Image.file(File(imagePath)) : Image.network(imagePath),
          ),
        );
      },
    );
  }

  void _details(String title, Map<String, dynamic> item) {
    print('details: ');
    print(item);
    List<String> images = [];

    if (item['image1'] != null) images.add(item['image1']);
    if (item['image2'] != null) images.add(item['image2']);
    if (item['image3'] != null) images.add(item['image3']);

    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text(title),
          contentPadding: EdgeInsets.all(8.0),
          content: SingleChildScrollView(
            child: Container(
              width: 300,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  if (images.isNotEmpty)
                    Row(
                      children: images.map((image) {
                        return GestureDetector(
                          onTap: () => _showImageDialog(image),
                          child: Padding(
                            padding: const EdgeInsets.only(left: 4.0, right: 4.0),
                            child: !image.contains('http') ? Image.file(
                              File(image),
                              height: 110,
                              width: 88,
                              fit: BoxFit.cover,
                            ) : Image.network(
                              image,
                              height: 110,
                              width: 88,
                              fit: BoxFit.cover,
                            ),
                          ),
                        );
                      }).toList(),
                    ),
                    SizedBox(height: 8.0),
                     Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        SizedBox(height: 8,),
                          RichText(
                            text: TextSpan(
                              children: [
                                TextSpan(
                                  text: 'Тайлбар: ',
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: Colors.black,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                TextSpan(
                                  text: item['comment'],
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: Colors.black,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          SizedBox(height: 8.0),
                          RichText(
                            text: TextSpan(
                              children: [
                                TextSpan(
                                  text: 'Төрөл: ',
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: Colors.black,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                TextSpan(
                                  text: typeName(item['type']),
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: Colors.black,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          SizedBox(height: 8.0,),
                          if (item['status'] != 0)
                          RichText(
                            text: TextSpan(
                              children: [
                                TextSpan(
                                  text: 'Статус: ',
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: Colors.black,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                TextSpan(
                                text: '${statusName(item['status'])}',
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: _getStatusColor(int.parse(item['status'])),
                                  ),
                                ),
                              ],
                            ),
                          ),
                          if (item['admin_comment'] != '') ...[
                          SizedBox(height: 8.0),
                          RichText(
                            text: TextSpan(
                              children: [
                                TextSpan(
                                  text: 'Админ: ',
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: Colors.black,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                TextSpan(
                                  text: item['admin_comment'],
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: Colors.black,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          ],
                          SizedBox(height: 8.0),
                          RichText(
                            text: TextSpan(
                              children: [
                                TextSpan(
                                  text: 'Огноо: ',
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: Colors.black,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                TextSpan(
                                  text: item['date'],
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: Colors.black,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                  ],
                ),
              ),
            ),
            actions: <Widget>[
            TextButton(
              child: Text('OK'),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
          ],
        );
      },
    );
  }

  void _showSendDialog(String title, Map<String, dynamic> item) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text(title),
          actions: <Widget>[
            TextButton(
              child: Text('Буцах'),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
            ElevatedButton(
              onPressed: () {
                send(item);
                Navigator.of(context).pop();
              },
              child: Text('OK'),
            ),
          ],
        );
      },
    );
  }

    void _showDeleteDialog(String title, Map<String, dynamic> item) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text(title),
          actions: <Widget>[
            TextButton(
              child: Text('Цуцлах'),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
            ElevatedButton(
              onPressed: () {
                deletepost(item['id']);
                Navigator.of(context).pop();
              },
              child: Text('OK'),
            ),
          ],
        );
      },
    );
  }

  List<DataColumn> _createColumns() {
    return [
      DataColumn(label: Text('Тайлбар')),
      DataColumn(label: Text('Төрөл')),
      DataColumn(label: Text('Статус')),
      DataColumn(label: Text('admin_comment')),
    ];
  }

  statusName(status){
    if(status == 1) {
      return 'Илгээсэн';
    } else {
      if(status == 0) {
        return 'Илгээгээгүй';
      }
      return statusNameGlobal.isEmpty ? '' : statusNameGlobal.firstWhere((item) => item['id'] == status)['name'];
    }
  }
  typeName(types) {
    return typeNameGlobal.isEmpty ? '' : typeNameGlobal.firstWhere((item) => item['id'] == types)['name'];
  }

  Color _getStatusColor(int status) {
    switch (status) {
      case 1:
        return const Color.fromARGB(255, 13, 96, 165);
      case 2:
        return Colors.orange;
      case 3: 
        return const Color.fromARGB(255, 34, 131, 37);
      case 4:
        return const Color.fromARGB(255, 199, 32, 20);
      default:
        return Colors.grey;
    }
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }
  
  Widget _cardBuild(Map<String, dynamic> item) {
    print(item['status']);
    List<String> images = [];
    if (item['image1'] != null) images.add(item['image1']);

    return Card(
      margin: EdgeInsets.all(8.0),
      child: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            colors: <Color>[
              Colors.white70,
              Colors.white54,
              Colors.white30,
            ],
          ),
          borderRadius: BorderRadius.circular(8.0),
        ),
        padding: EdgeInsets.all(8.0),
        width: 305,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            if (images.isNotEmpty)
            Row(
              // crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  padding: const EdgeInsets.only(),
                  decoration: BoxDecoration(
                    border: Border.all(color: const Color.fromARGB(255, 195, 223, 196), width: 2),
                    borderRadius: BorderRadius.circular(5.0),
                  ),
                  child: !images[0].contains('http') ? Image.file(
                    File(images[0]),
                    height: 140,
                  ) : Image.network(
                    images[0],
                    height: 140,
                  ),
                ),
                SizedBox(height: 15, width: 5,),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    SizedBox(height: 8,),
                    RichText(
                      text: TextSpan(
                        children: [
                          TextSpan(
                            text: 'Тайлбар: ',
                            style: TextStyle(
                              fontSize: 14,
                              color: Colors.black,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          TextSpan(
                            text: '...',
                            style: TextStyle(
                              fontSize: 14,
                              color: Colors.black,
                            ),
                          ),
                        ],
                      ),
                    ),
                    SizedBox(height: 8.0),
                    RichText(
                      text: TextSpan(
                        children: [
                          TextSpan(
                            text: 'Төрөл: ',
                            style: TextStyle(
                              fontSize: 14,
                              color: Colors.black,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          TextSpan(
                            text: typeName(item['type']),
                            style: TextStyle(
                              fontSize: 14,
                              color: Colors.black,
                            ),
                          ),
                        ],
                      ),
                    ),
                    SizedBox(height: 8.0),
                    if (item['status'] != 0)
                      RichText(
                        text: TextSpan(
                          children: [
                            TextSpan(
                              text: 'Статус: ',
                              style: TextStyle(
                                fontSize: 14,
                                color: Colors.black,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            TextSpan(
                              text: statusName(item['status']).substring(
                                0, min<int>(12, statusName(item['status']).length)
                              ),
                              style: TextStyle(
                                fontSize: 14,
                                color: _getStatusColor(int.parse(item['status'])),
                              ),
                            ),
                          ],
                        ),
                      ),
                      if (statusName(item['status']).length > 12) ...[
                        SizedBox(height: 8.0),
                        RichText(
                          text: TextSpan(
                            children: [
                              TextSpan(
                                text: statusName(item['status']).substring(
                                  12, 
                                  min<int>(40, statusName(item['status']).length)
                                ),
                                style: TextStyle(
                                  fontSize: 14,
                                  color: _getStatusColor(int.parse(item['status'])),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    if (item['admin_comment'] != '') ...[
                    SizedBox(height: 8.0),
                    RichText(
                      text: TextSpan(
                        children: [
                          TextSpan(
                            text: 'Админ: ',
                            style: TextStyle(
                              fontSize: 14,
                              color: Colors.black,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          TextSpan(
                            text: "...",
                            style: TextStyle(
                              fontSize: 14,
                              color: Colors.black,
                            ),
                          ),
                        ],
                      ),
                    ),
                    ],
                    SizedBox(height: 8.0),
                    RichText(
                      text: TextSpan(
                        children: [
                          TextSpan(
                            text: 'Огноо: ',
                            style: TextStyle(
                              fontSize: 14,
                              color: Colors.black,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          TextSpan(
                            text: item['date'],
                            style: TextStyle(
                              fontSize: 14,
                              color: Colors.black,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ],
            ),
            const Divider(
              height: 5,
              color: Colors.green,
            ),
            SizedBox(height: 2),
            item['status'] == 0 
            ? 
            Row(
              children: [
                Container(
                  height: 35,
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
                      'Илгээх',
                      style: TextStyle(
                        fontSize: 14,
                        color: Colors.white,
                      ),
                    ),
                    onPressed: () {
                      _showSendDialog('Илгээх', item);
                      print(item);
                    },
                    style: TextButton.styleFrom(
                      padding: EdgeInsets.symmetric(horizontal: 12.0, vertical: 8.0),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8.0),
                      ),
                    ),
                  ),
                ),
                SizedBox(width: 8,),
                Container(
                  height: 35,
                  decoration: BoxDecoration(
                    gradient: LinearGradient(
                      colors: <Color>[
                        Color(0xFFd32f2f),
                        Color(0xFFf44336),
                        Color(0xFFe57373),
                      ],
                    ),
                    borderRadius: BorderRadius.circular(8.0),
                  ),
                  child: TextButton(
                    child: Text(
                      'Устгах',
                      style: TextStyle(fontSize: 14, color: Colors.white),
                    ),
                    onPressed: () async {
                      _showDeleteDialog('Устгах', item);
                    },
                    style: TextButton.styleFrom(
                      padding: EdgeInsets.symmetric(horizontal: 12.0, vertical: 8.0),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8.0),
                      ),
                    ),
                  ),
                ),
              ],
            ) : Row(),
            SizedBox(height: 5, width: 20,),
            Row(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                Container(
                  height: 35,
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
                      'Дэлгэрэнгүй',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    onPressed: () {
                      setState(() {
                        isDetailsClicked = true;
                      });
                      _details('Дэлгэрэнгүй', item);
                    },
                    style: TextButton.styleFrom(
                      padding: EdgeInsets.symmetric(horizontal: 12.0, vertical: 6.0),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8.0),
                      ),
                      backgroundColor: Colors.transparent,
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    List<dynamic> notSentItem = data.where((item) => item['status'] == 0).toList();
    List<dynamic> sentItem = data.where((item) => item['status'] != 0).toList();
    return Scaffold(
      key: _scaffoldGlobalKey,
      endDrawer: Menu(),
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
      ),
      body: Stack(
        children: [
          Positioned.fill(
            child: Image.asset('lib/assets/background.jpg', fit: BoxFit.cover,),
          ),
          Padding(
            padding: const EdgeInsets.all(BorderSide.strokeAlignCenter),
            child: Column(
              children: <Widget>[
                SizedBox(height: 0),
                Expanded(
                  child: SmartRefresher(
                    controller: _refreshController,
                    onRefresh: _onRefresh,
                    onLoading: _onLoading,
                    child: ListView(
                      padding: EdgeInsets.all(0),
                      children: [
                        Container(
                          width: double.minPositive,
                          padding: EdgeInsets.all(10),
                          decoration: BoxDecoration(
                            color: const Color.fromARGB(255, 11, 65, 38),
                            // borderRadius: BorderRadius.circular(8.0),
                            borderRadius: BorderRadius.only(bottomLeft: Radius.circular(30), bottomRight: Radius.circular(30)),
                          ),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Text(
                                'Мэдэгдлийн жагсаалт',
                                style: 
                                  TextStyle(
                                  fontSize: 18,
                                  fontWeight: FontWeight.bold,
                                  color: Colors.white,
                                  )
                                // style: GoogleFonts.lato(
                                //   textStyle: TextStyle(
                                //   fontSize: 18,
                                //   fontWeight: FontWeight.bold,
                                //   color: Colors.white,
                                //   )
                                // ),
                              ),
                            ],
                          ),
                        ),
                        Container(
                          // color: Colors.blueGrey,
                          height: 100,
                          padding: EdgeInsets.all(8.0,),
                          width: double.infinity,
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.center,
                            children: [
                              SizedBox(height: 10),
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                                children: [
                                  Expanded(
                                    child: 
                                    Column(
                                      crossAxisAlignment: CrossAxisAlignment.center,
                                      children: [
                                        Text('Нийт', style: TextStyle(color: Colors.white, fontSize: 16, fontFamily: 'Roboto', fontWeight: FontWeight.bold,)),
                                        SizedBox(height: 5),
                                        Text('$allPostsCount', style: TextStyle(color: Colors.white, fontSize: 22)),
                                      ],
                                    ),
                                  ),
                                  Expanded(
                                    child: 
                                    Column(
                                      crossAxisAlignment: CrossAxisAlignment.center,
                                      children: [
                                        Text('Илгээсэн', style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold,)),
                                        SizedBox(height: 5),
                                        Text('$sendPostsCount', style: TextStyle(color: Colors.white, fontSize: 22)),
                                      ],
                                    ),
                                  ),
                                  Expanded(
                                    child: 
                                    Column(
                                      crossAxisAlignment: CrossAxisAlignment.center,
                                      children: [
                                        Text('Илгээгдээгүй', style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold,)),
                                        SizedBox(height: 5),
                                        Text('$unsendPostsCount', style: TextStyle(color: Color(0xFFe57373), fontSize: 22)),
                                      ],
                                    ),
                                  )
                                ],
                              ),
                            ],
                          ),
                        ),
                        if (sendPostsCount != 0 || unsendPostsCount != 0) ...[
                          SizedBox(height: 0,),
                          DefaultTabController(
                            length: 2,
                            child: Column(
                              children: <Widget>[
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: [
                                     Container(
                                      width: 150,
                                      height: 45,
                                      decoration: BoxDecoration(
                                        gradient: isSelectButton
                                        ? LinearGradient(
                                          colors: <Color>[
                                            Color(0xFF2a7d2e),
                                            Color(0xFF1b5e20),
                                            Color(0xFF66bb6a),
                                          ],
                                        ) : null,
                                        color: isSelectButton ? null : Colors.grey[300],
                                        borderRadius: BorderRadius.circular(8.0),
                                        ),
                                        child: TextButton(
                                          onPressed: () {
                                          setState(() {
                                            showItem = true;
                                            isSelectButton = true;
                                          });
                                        },
                                        child: Row(
                                          mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                                          children: [
                                            Icon(
                                              Icons.send,
                                              color: !isSelectButton ? Colors.black : Colors.white,
                                              size: 20,
                                            ),
                                            // SizedBox(width: 8,),
                                            Text(
                                              'Илгээсэн', 
                                              style: TextStyle(
                                                color: !isSelectButton ? Colors.black : Colors.white,
                                                fontSize: 15,
                                                fontWeight: FontWeight.bold,
                                              ),
                                            ),
                                          ]
                                        ),
                                      ),
                                    ),
                                    SizedBox(width: 10,),
                                     Container(
                                      width: 150,
                                      height: 45,
                                      decoration: BoxDecoration(
                                        gradient: !isSelectButton
                                        ? LinearGradient(
                                          colors: <Color>[
                                            Color(0xFF2a7d2e),
                                            Color(0xFF1b5e20),
                                            Color(0xFF66bb6a),
                                          ],
                                        ) : null,
                                        color: !isSelectButton ? null : Colors.grey[300],
                                        borderRadius: BorderRadius.circular(8.0),
                                      ),
                                      child: TextButton(
                                        onPressed: () {
                                          setState(() {
                                            showItem = false;
                                            isSelectButton = false;
                                          });
                                        },
                                        child: Row(
                                          mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                                          children: [
                                            Icon(
                                              Icons.warning, // Replace with your desired icon
                                              color: !isSelectButton ? Colors.white : Colors.black,
                                              size: 20, // Adjust the size as needed
                                            ),
                                            // SizedBox(width: 8,),
                                              Text(
                                                'Илгээгээгүй', 
                                                style: TextStyle(
                                                color: !isSelectButton ? Colors.white : Colors.black,
                                                  // color: Colors.white,
                                                  fontSize: 15,
                                                  fontWeight: FontWeight.bold,
                                                ),
                                              ),
                                          ],
                                        ), 
                                      ),
                                    )
                                  ],
                                ),
                                Container(
                                  height: 260.0,
                                  child: showItem
                                  ? sentItem.isEmpty
                                  ? Padding(
                                      padding: const EdgeInsets.all(8.0),
                                      child: Text(
                                        'Хоосон',
                                        style: TextStyle(fontSize: 16, color: Colors.grey),
                                      ),
                                    )
                                  : SingleChildScrollView(
                                      scrollDirection: Axis.horizontal,
                                      child: Row(
                                        children: sentItem.map((item) => _cardBuild(item)).toList(),
                                      ),
                                    )
                                  : notSentItem.isEmpty
                                  ? Padding(
                                      padding: const EdgeInsets.all(8.0),
                                      child: Text(
                                        'Хоосон',
                                        style: TextStyle(fontSize: 16, color: Colors.grey),
                                      ),
                                    )
                                  : SingleChildScrollView(
                                      scrollDirection: Axis.horizontal,
                                      child: Row(
                                        children: notSentItem.map((item) => _cardBuild(item)).toList(),
                                      ),
                                    ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ],
                    ),
                  ),
                )
              ],
            ),
          ),
        ],
      ),
    );
  }
}
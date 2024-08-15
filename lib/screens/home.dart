import 'dart:convert';
import 'dart:io';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';
import 'package:my_flutter_project/main.dart';
import 'package:my_flutter_project/screens/menu.dart';
import 'package:pull_to_refresh_flutter3/pull_to_refresh_flutter3.dart';
import '../database.dart';
// import 'package:buttons_tabbar/buttons_tabbar.dart';
// import 'package:google_fonts/google_fonts.dart';


class Home extends StatefulWidget {
  const Home({super.key});

  @override
  State<Home> createState() => _HomeState();
}

class _HomeState extends State<Home> with SingleTickerProviderStateMixin {
  final _sqliteService = DatabaseHelper.instance;
  int currentIndex = 0;
  List<Map<String, dynamic>> data = [];
  String _message = '';
  bool _isLoading = false;
  RefreshController  _refreshController  = RefreshController(initialRefresh: false);
  bool isDetailsClicked = false;
  late AnimationController _controller;
  late Animation<Color?> _colorAnimation;

  String _showdialog = 'Амжилттай илгээлээ';
  String _dialogSend = 'Илгээх';
  int age = 10;

  String errMessage = '';
  int allPostsCount = 0;
  int sendPostsCount = 0;
  int unsendPostsCount = 0;

  @override
  initState() {
    super.initState();
    fetchStatus();
    allPostCount();
    getCounts();
    _controller = AnimationController(
      duration: const Duration(seconds: 1),
      vsync: this,
    )..repeat(reverse: true);

    _colorAnimation = ColorTween(
      begin: Colors.red,
      end: Colors.transparent,
    ).animate(_controller);
  }

  Future<void> getCounts() async{
    DatabaseHelper dbHelper = DatabaseHelper.instance;
    int sendPosts = await dbHelper.getSendPostsCount();
    int posts = await dbHelper.getUnsendPostsCount();

    setState(() {
      sendPostsCount = sendPosts;
      unsendPostsCount = posts;
      print(sendPosts);
      print(posts);
    });
  }

  Future<void> getpost() async {
    data.clear();
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      await _sqliteService.getPosts().then((res) {
        setState(() {
          data.addAll(res);
        });
      });
      await _sqliteService.getSendPosts().then((res) {
        setState(() {
          data.addAll(res);
        });
      });
    });
  }

  Future<void> _onRefresh() async {
    fetchStatus();
    _refreshController.refreshCompleted();
    allPostCount();
    getCounts();
    print('working scroll...');
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
          getpost();
          print('deleted post');
        });
      });
    });
  }

  Future<void> send(Map<String, dynamic> item) async {
    try {
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
        Uri.parse('${apiUrl}posts'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8'
        },
        body: jsonEncode(body),
      );

      if (response.statusCode == 200) {
        setState(() {
          print(response.body);
          final responseBody = json.decode(response.body);
          print(responseBody['id'].toString());
          print(responseBody['post']['status'].toString());
          final Map<String, dynamic> row = {
            'id': responseBody['id'],
            'name': item['name'],
            'number': item['number'],
            'comment': item['comment'],
            'type': item['type'],
            'status': 1,
            'image1': item['image1'],
            'image2': item['image2'],
            'image3': item['image3'],
            'latitude': item['latitude'],
            'longitude': item['longitude'],
            'date' : formattedDate
          };
          _sqliteService.insertSendPost(row);
          deletepost(item['id']);
          _showDialog(_showdialog, '.....');
        });
      } else {
        setState(() {
          _showMessage('Failed to send: ${response.statusCode}');
          print('failed response');
          print(response.statusCode);
        });
      }
    } catch (e) {
      setState(() {
          _showMessage('ERROR: No Internet Connection');
      });
    }
  }

  Future<void> allPostCount() async {
    try {
    final http.Response response = await http.post(
    Uri.parse('${apiUrl}count'),
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
      },
    );
    if (response.statusCode == 200) {
      final Map<String, dynamic> responceData = jsonDecode(response.body);
      setState(() {
        allPostsCount = responceData['all'];
        print(allPostsCount);
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
        Uri.parse('${apiUrl}myposts'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8'
        },
        body: jsonEncode(body),
      );
      print(response.body);
      setState(() {
        if(response.statusCode == 200) {
          final List<dynamic> responseData = jsonDecode(response.body);
            for (var item in responseData) {
              final Map<String, dynamic> row = {
                'status': item['status'],
                'admin_comment': item['admin_comment'],
              };
              _sqliteService.updatePostStatus(row, item['id']);
            }
            getpost();
        }
      });
  }

  void _showMessage(String message) {
    setState(() {
      _message = message;
    });
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        duration: Duration(seconds: 3),
      ),
    );
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
            child: Image.file(File(imagePath)),
          ),
        );
      },
    );
  }

  void _details(String title, Map<String, dynamic> item) {
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
                            child: Image.file(
                              File(image),
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
                                    color: _getStatusColor(item['status']),
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
              child: Text('Цуцлах'),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
            ElevatedButton(
              onPressed: () {
                send(item);
                print('call the send function');
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
                print('call the delete function');
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
    List<String> name = ['','Илгээсэн','Хүлээн авсан','Шийдвэрлэсэн','Татгалзсан'];
    return name[status];
  }

  typeName(types) {
    List<String> name = ['', 'Бусад', 'Хог хягдал', 'эвдрэл доройтол', 'Бохир'];
    return name[types];
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
    List<String> images = [];
    if (item['image1'] != null) images.add(item['image1']);

    if (isDetailsClicked || item['status'] != 1) {
      _controller.stop();
    } else {
      _controller.repeat(reverse: true);
    }

    return AnimatedBuilder(
      animation: _controller,
      builder: (context, child) {
        return Card(
          margin: EdgeInsets.all(8.0),
          shape: RoundedRectangleBorder(
            side: BorderSide(
              color: !isDetailsClicked && item['status'] != 1 && item['status'] != 0
                ? _colorAnimation.value!
                : Colors.transparent,
              width: 2.0,
            ),
            borderRadius: BorderRadius.circular(8.0)
          ),
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
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                if (images.isNotEmpty)
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      padding: const EdgeInsets.only(),
                      decoration: BoxDecoration(
                        border: Border.all(color: const Color.fromARGB(255, 195, 223, 196), width: 2),
                        borderRadius: BorderRadius.circular(5.0),
                      ),
                      child: Image.file(
                        File(images[0]),
                        height: 140,
                      ),
                    ),
                    SizedBox(height: 15, width: 15,),
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
                                  color: _getStatusColor(item['status']),
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
                  crossAxisAlignment: CrossAxisAlignment.end,
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
    );
  }

  @override
  Widget build(BuildContext context) {
    List<Map<String, dynamic>> notSentItem = data.where((item) => item['status'] == 0).toList();
    List<Map<String, dynamic>> sentItem = data.where((item) => item['status'] != 0).toList();
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
                image: AssetImage('lib/assets/NBOG-logo.png'),
              ),
            ),
          ],
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
        ) : Stack(
        children: [
          Positioned.fill(
            child: Image.asset('lib/assets/background.jpg', fit: BoxFit.cover,),
          ),
          Padding(
            padding: const EdgeInsets.all(BorderSide.strokeAlignCenter),
            child: Column(
              children: <Widget>[
                if (_isLoading) CircularProgressIndicator(),
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
                                'Санал хүсэлтийн жагсаалт',
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
                                // ButtonsTabBar(
                                //   radius: 12,
                                //   contentPadding: EdgeInsets.symmetric(horizontal: 12),
                                //   borderWidth: 2,
                                //   borderColor: Colors.transparent,
                                //   center: true,
                                //   decoration: BoxDecoration(
                                //     gradient: LinearGradient(
                                //       colors: <Color>[
                                //       Color(0xFF2a7d2e),
                                //       Color(0xFF1b5e20),
                                //       Color(0xFF66bb6a),
                                //       ],
                                //     ),
                                //   ),
                                //   labelStyle: TextStyle(
                                //     color: Colors.white,
                                //     fontWeight: FontWeight.bold,
                                //   ),
                                //   unselectedLabelStyle: TextStyle(
                                //     color: Colors.black.withOpacity(0.5),
                                //     fontWeight: FontWeight.bold,
                                //     backgroundColor: Colors.transparent,
                                //   ),
                                //   tabs: [
                                //     Tab(
                                //       icon: Icon(Icons.send),
                                //       text: 'Илгээсэн',
                                //     ),
                                //     Tab(
                                //       icon: Icon(Icons.warning),
                                //       text: 'Илгээгээгүй',
                                //     )
                                //   ],
                                // ),
                                Container(
                                  height: 260.0,
                                  child: TabBarView(
                                    children: [
                                    sentItem.isEmpty
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
                                    ),
                                    notSentItem.isEmpty
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
                                    ],
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
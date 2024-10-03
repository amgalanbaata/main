import 'package:flutter/material.dart';
import 'package:my_flutter_project/screens/menu.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:geolocator/geolocator.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:my_flutter_project/main.dart';
import 'package:http/http.dart' as http;


class Home extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}
WebViewController controller = WebViewController();

class _HomePageState extends State<Home> {
  String response = '';
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    checkLocationService();
    getLocation();
  }

  void checkLocationService() async {
    print('call checkLocationService');
    bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if(!serviceEnabled) {
      await Geolocator.openLocationSettings();

      serviceEnabled = await Geolocator.isLocationServiceEnabled();
    }

    if (serviceEnabled) {
      getLocation();
    } else {
      showLocationErrorDialog();
    }
  }

  void showLocationErrorDialog() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Location Services Disabled'),
          content: Text('Please enable location services to continue.'),
          actions: [
            TextButton(
              child: Text('OK'),
              onPressed: () {
                Navigator.of(context).pop();
                // checkLocationService();
              },
            ),
          ],
        );
      },
    );
  }

  void showPermissionDeniedDialog() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Location Permission Denied'),
          content: Text('Please grant location permission to continue.'),
          actions: [
            TextButton(
              child: Text('Retry'),
              onPressed: () {
                Navigator.of(context).pop();
                // getLocation();
              },
            ),
          ],
        );
      },
    );
  }

  Future<void> getLocation() async {
    PermissionStatus status = await Permission.location.request();

    try {
      Position position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
      );
      
      String latitude = position.latitude.toString();
      String longitude = position.longitude.toString();

      final Uri url = Uri.parse('${apiUrl}map?lat=$latitude&lon=$longitude');
      
      final http.Response response = await http.post(
        url,
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8'
        },
      );
      print('${apiUrl}map?lat=$latitude&lon=$longitude');

      print('Location data sent: $latitude, $longitude');
      print('Response: ${response.body}');
      
      controller = WebViewController()
    ..setJavaScriptMode(JavaScriptMode.unrestricted)
    ..setBackgroundColor(const Color(0x00000000))
    ..setNavigationDelegate(
      NavigationDelegate(
        onProgress: (int progress) {
          // Update loading bar.
        },
        onPageStarted: (String url) {},
        onPageFinished: (String url) {},
        onWebResourceError: (WebResourceError error) {},
      ),
    )
    ..loadRequest(Uri.parse('${apiUrl}map?lat=$latitude&lon=$longitude'));
    print('${apiUrl}map?lat=$latitude&lon=$longitude');
      setState((){
        isLoading = false;
      });
    } catch (e) {
      print('Error posting location: $e');
      controller = WebViewController()
    ..setJavaScriptMode(JavaScriptMode.unrestricted)
    ..setBackgroundColor(const Color(0x00000000))
    ..setNavigationDelegate(
      NavigationDelegate(
        onProgress: (int progress) {
          // Update loading bar.
        },
        onPageStarted: (String url) {},
        onPageFinished: (String url) {},
        onWebResourceError: (WebResourceError error) {},
      ),
    )
    ..loadRequest(Uri.parse('${apiUrl}map'));
      setState((){
        isLoading = false;
      });
    }
  }

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
      body: Stack(
        children: [
          Column(
            children: [
              Container(
                width: double.maxFinite,
                padding: EdgeInsets.all(10),
                decoration: BoxDecoration(
                  color: const Color.fromARGB(255, 11, 65, 38),
                  borderRadius: BorderRadius.only(bottomLeft: Radius.circular(10), bottomRight: Radius.circular(10)),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      'Хөрсний бохирдлыг цэгээр харуулав',
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
              Expanded(
                child: !isLoading 
                    ? WebViewWidget(controller: controller) 
                    : Container(),
              ),
            ],
          ),
          if (isLoading)
            Center(
              child: CircularProgressIndicator(),
            ),
        ],
      ),
    );
  }
}
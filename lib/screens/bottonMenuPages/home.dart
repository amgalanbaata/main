import 'package:flutter/material.dart';
import 'package:ubsoil/screens/menu.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:geolocator/geolocator.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:ubsoil/main.dart';
import 'package:http/http.dart' as http;
import 'package:ubsoil/screens/constant/data.dart';

class Home extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}
WebViewController controller = WebViewController();
GlobalKey _scaffoldGlobalKey = GlobalKey();

class _HomePageState extends State<Home> {
  String response = '';
  int loadWeb = 1;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      showLoader(_scaffoldGlobalKey);
      checkLocationService();
    });
  }

  @override
  void dispose() {
    super.dispose();
  }

  void checkLocationService() async {
    
    bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
    
    if(!serviceEnabled) {
      await Geolocator.openLocationSettings();

      serviceEnabled = await Geolocator.isLocationServiceEnabled();
      hideLoader(_scaffoldGlobalKey);
    }

    if (serviceEnabled) {
      getLocation();
    } else {
      showLocationErrorDialog();
      getLocation();
    }    
  }

  void showLocationErrorDialog() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Байршлын үйлчилгээг идэвхгүй болгосон'),
          content: Text('Үргэлжлүүлэхийн тулд байршлын үйлчилгээг идэвхжүүлнэ үү.'),
          actions: [
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

  try {
    PermissionStatus status = await Permission.location.request();
    
    if (status.isGranted) {
      Position position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
      );

      String latitude = position.latitude.toString();
      String longitude = position.longitude.toString();

      final Uri url = Uri.parse('${apiUrl}map?lat=$latitude&lon=$longitude');

      setState(() {
        controller = WebViewController()
          ..setJavaScriptMode(JavaScriptMode.unrestricted)
          ..setBackgroundColor(const Color(0x00000000))
          ..setNavigationDelegate(
            NavigationDelegate(
              onProgress: (int progress) {},
              onPageStarted: (String url) {},
              onPageFinished: (String url) {},
              onWebResourceError: (WebResourceError error) {
                print('AAA:' + error.errorCode.toString());
                if(error.errorCode == -2) {
                  setState(() {
                    loadWeb = 0;
                  });
                }
              },
            ),
          )
          ..loadRequest(url);
      });
      hideLoader(_scaffoldGlobalKey);
    } else {
      // showPermissionDeniedDialog();
      loadDefaultMap();
    }
  } catch (e) {
    print('Error fetching location: $e');
    loadDefaultMap();
  }
}

void loadDefaultMap() {
  hideLoader(_scaffoldGlobalKey);
  setState(() {
    controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(const Color(0x00000000))
      ..setNavigationDelegate(
        NavigationDelegate(
          onProgress: (int progress) {},
          onPageStarted: (String url) {},
          onPageFinished: (String url) {},
          onWebResourceError: (WebResourceError error) {},
        ),
      )
      ..loadRequest(Uri.parse('${apiUrl}map'));
  });  
}

  @override
  Widget build(BuildContext context) {
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
                      'Хөрсний бохирдлын байршил',
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
                child: loadWeb == 1 ? WebViewWidget(controller: controller) 
                : Container(
                  padding: EdgeInsets.symmetric(horizontal: 20.0, vertical: 50.0),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    crossAxisAlignment: CrossAxisAlignment.center,
                    children: [
                      Text(
                        'Интэрнэт холболт байхгүй .....',
                        textAlign: TextAlign.center,
                        style: TextStyle(
                          fontSize: 18.0,
                          fontWeight: FontWeight.bold,
                          color: const Color.fromARGB(255, 11, 65, 38),
                        ),
                      ),
                    ],
                  ),
                )
              ),
            ],
          ),
        ],
      ),
    );
  }
}
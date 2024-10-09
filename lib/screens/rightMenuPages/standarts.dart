import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:my_flutter_project/main.dart';

class StandartsPageView extends StatefulWidget {
  @override
  _StandartsState createState() => _StandartsState();
}

class _StandartsState extends State<StandartsPageView> {
  bool isLoading = true;
  late WebViewController controller;

  @override
  void initState() {
    super.initState();

    controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setNavigationDelegate(
        NavigationDelegate(
          onPageStarted: (String url) {
            setState(() {
              isLoading = true;
            });
          },
          onPageFinished: (String url) {
            setState(() {
              isLoading = false;
            });
          },
          onWebResourceError: (WebResourceError error) {
            setState(() {
              isLoading = false;
            });
          },
        ),
      )
      ..loadRequest(Uri.parse('${apiUrl}standarts'));
  }
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Хөрсний Стандартууд'),
      ),
      body: Stack(
        children: [
          WebViewWidget(controller: controller),
          Container(),
          if (isLoading)
            Center(
              child: CircularProgressIndicator(),
            ),
        ],
      ),
    );
  }
}
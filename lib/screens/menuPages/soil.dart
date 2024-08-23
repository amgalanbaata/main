import 'package:flutter/material.dart';
import 'package:my_flutter_project/main.dart';
import 'package:webview_flutter/webview_flutter.dart';

class SoilWebView extends StatefulWidget {
  @override
  _SoilWebViewState createState() => _SoilWebViewState();
}

class _SoilWebViewState extends State<SoilWebView> {
  bool isLoading = true;

  @override
  Widget build(BuildContext context) {
    WebViewController controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(const Color(0x00000000))
      // ..setNavigationDelegate(
      //   NavigationDelegate(
      //     onPageStarted: (String url) {
      //       setState(() {
      //         isLoading = true;
      //       });
      //     },
      //     onPageFinished: (String url) {
      //       setState(() {
      //         isLoading = false;
      //       });
      //     },
      //     onWebResourceError: (WebResourceError error) {
      //       setState(() {
      //         isLoading = false;
      //       });
      //     },
      //   ),
      // )
      ..loadRequest(Uri.parse('${apiUrl}soil'));

    return Scaffold(
      appBar: AppBar(
        title: Text('Хөрсний шинжилгээний мэдээлэл'),
      ),
      body: Stack(
        children: [
          WebViewWidget(controller: controller),
          if (!isLoading)
            Center(
              child: CircularProgressIndicator(),
            ),
        ],
      ),
    );
  }
}

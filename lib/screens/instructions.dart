import 'package:flutter/material.dart';
import 'package:ubsoil/main.dart';
import 'package:ubsoil/screens/bottonMenuPages/navigationBar.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:shared_preferences/shared_preferences.dart';

class InstructionsPageView extends StatefulWidget {
  @override
  _InstructionsState createState() => _InstructionsState();
}

class _InstructionsState extends State<InstructionsPageView> {
  bool _isAgreed = false;

  @override
  void initState() {
    super.initState();
  }

  
  void _navigateToHome() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    await prefs.setBool('isAgreed', true);
    Navigator.pop(context);
      Navigator.push(
        context,
        MaterialPageRoute(builder: (context) => MainApp()),
      );
      return;
  }

  void _toastMessage(String title, String message) {
    Fluttertoast.showToast(
        msg: message,
        toastLength: Toast.LENGTH_SHORT,
        gravity: ToastGravity.BOTTOM,
        timeInSecForIosWeb: 3,
        backgroundColor: Colors.red,
        textColor: Colors.white,
        fontSize: 16.0
    );
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
            color: Colors.green,
            height: 2.0,
          ),
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: SingleChildScrollView (
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              SizedBox(width: 8),
              Center(
                child: Text(
                "Ашиглах заавар",
                style: TextStyle(fontSize: 20),
                )
              ),
              SizedBox(height: 10),
              SizedBox(height: 300,
                child: SingleChildScrollView (
                  child: Text(
                          'Хөрсний бохирдлын талаар мэдээлэл хүргэхдээ дараах нөхцөлүүдийг хангана уу:\n\n'
                          '1. Хуурамч болон холбогдолгүй мэдээлэл илгээхгүй байх.\n'
                          '2. Орон сууцны хороолол буюу бетонон дундах цэг шаардлагагүй\n'
                          '3. Зөвхөн хөрстэй холбоотой байршил байх.\n'
                          '4. Тухайн байршил дээр очиж зураг дарж илгээх (хамгийн ихдээ 3 зураг)\n'
                          '5. Хог хаягдал бол хатуу биетээс үүдсэн, эвдрэл доройтол бол бусад хүчин зүйлсээс үүдсэн, бохир бол шингэн зүйлээс үүдсэн гэж үзнэ\n'
                          '6. Таны илгээсэн мэдээлэлд байгаль орчны газрын ажилчид хариу өгөх ба шууд АПП дээрээ хараад явах боломжтой\n'
                          '7. Та мөн интернетгүй орчинд илгээх мэдээллээ хадгалаад интернеттэй орчинд ирсний дараа дахин илгээх боломжтой\n\n\n',
                          style: TextStyle(
                            fontSize: 16,
                            height: 1.5,
                            color: Colors.black87,
                          ),
                        )
                  )
                ),              
              SizedBox(height: 10),
              Center(
                child: 
                  CheckboxListTile(
                    title: Text(
                      'Заавартай танилцав',
                      style: TextStyle(fontSize: 14, color: Colors.black),
                    ),
                    value: _isAgreed,
                    onChanged: (bool? value) {
                      setState(() {
                        _isAgreed = value ?? false;
                        print(_isAgreed);
                      });
                    },
                    controlAffinity: ListTileControlAffinity.leading,
                    activeColor: Colors.green,
                  ),
              ),
              SizedBox(height: 10),
              Center(
                child: ElevatedButton(
                  onPressed: () {
                    if(_isAgreed) {
                      _navigateToHome();
                    } else {
                      _toastMessage('title', 'Зөвшөөрөөгүй байна ');
                    }
                  },
                  child: Text('Үргэлжлүүлэх'),
                  style: ElevatedButton.styleFrom(
                    padding: EdgeInsets.symmetric(horizontal: 32, vertical: 12),
                    // primary: Colors.green,
                    // onPrimary: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8.0),
                    ),
                  ),
                ),
              ),
            ],
          ),
        )
      ),
    );
  }
}
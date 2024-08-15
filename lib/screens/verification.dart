// import 'package:flutter/material.dart';
// import 'package:firebase_auth/firebase_auth.dart';
// import 'package:shared_preferences/shared_preferences.dart';
// import 'home.dart';

// class VerificationScreen extends StatefulWidget {
//   final String verificationId;
//   final String name;
//   final String number;
//   final bool isDevelopment;

//   const VerificationScreen({
//     Key? key,
//     required this.verificationId,
//     required this.name,
//     required this.number,
//     required this.isDevelopment,
//   }) : super(key: key);

//   @override
//   _VerificationScreenState createState() => _VerificationScreenState();
// }

// class _VerificationScreenState extends State<VerificationScreen> {
//   final _codeController = TextEditingController();
//   final FirebaseAuth _auth = FirebaseAuth.instance;

//   Future<void> _verifyCode() async {
//     String smsCode = widget.isDevelopment ? '1234' : _codeController.text;
//     if (smsCode.isNotEmpty) {
//       PhoneAuthCredential credential = PhoneAuthProvider.credential(
//         verificationId: widget.verificationId,
//         smsCode: smsCode,
//       );
//       await _auth.signInWithCredential(credential);
//       await _storeUserInformation(widget.name, widget.number);
//       _navigateToHome();
//     } else {
//       print('SMS кодоо оруулна уу');
//     }
//   }

//   Future<void> _storeUserInformation(String name, String number) async {
//     final SharedPreferences prefs = await SharedPreferences.getInstance();
//     await prefs.setString('name', name);
//     await prefs.setString('number', number);
//   }

//   void _navigateToHome() {
//     Navigator.pushReplacement(
//       context,
//       MaterialPageRoute(builder: (context) => Home()),
//     );
//   }

//   @override
//   void dispose() {
//     _codeController.dispose();
//     super.dispose();
//   }

//   @override
//   Widget build(BuildContext context) {
//     return Scaffold(
//       appBar: AppBar(
//         title: Text('Код шалгах'),
//       ),
//       body: Padding(
//         padding: const EdgeInsets.all(16.0),
//         child: SingleChildScrollView(
//           child: Column(
//             crossAxisAlignment: CrossAxisAlignment.stretch,
//             children: [
//               Text(
//                 'SMS код оруулна уу',
//                 style: TextStyle(
//                   fontSize: 24,
//                   fontWeight: FontWeight.bold,
//                 ),
//                 textAlign: TextAlign.center,
//               ),
//               SizedBox(height: 20),
//               TextFormField(
//                 controller: _codeController,
//                 decoration: InputDecoration(
//                   labelText: 'SMS код',
//                   prefixIcon: Icon(Icons.sms),
//                   border: OutlineInputBorder(),
//                 ),
//                 keyboardType: TextInputType.number,
//               ),
//               SizedBox(height: 20),
//               ElevatedButton(
//                 onPressed: _verifyCode,
//                 child: Text('Код шалгах'),
//               ),
//             ],
//           ),
//         ),
//       ),
//     );
//   }
// }

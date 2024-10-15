import 'package:uiblock/uiblock.dart';
import 'package:flutter/material.dart';

class ApiConstants {
  static String uuid = '';
}
showLoader(_scaffoldGlobalKey) {
  UIBlock.blockWithData(
    _scaffoldGlobalKey.currentContext, 
    customLoaderChild: const CircularProgressIndicator(
      valueColor: AlwaysStoppedAnimation<Color>(Color(0xffF7931E)),
    ),
  );
}

hideLoader(_scaffoldGlobalKey) {
  UIBlock.unblock(_scaffoldGlobalKey.currentContext);
}
import 'dart:io';
import 'package:path/path.dart';
import 'package:sqflite/sqflite.dart';
class DatabaseHelper {
  static final _databaseName = "NBOG.db";
  DatabaseHelper._privateConstructor();
  static final DatabaseHelper instance = DatabaseHelper._privateConstructor();
  
  Database? _database;
  Future<Database?> get database async {
    if (_database != null) return _database;
    _database = await _initDatabase();
    return _database;
  }
  _initDatabase() async {
    String path = join(await getDatabasesPath(), _databaseName);
    return await openDatabase(path,
        version: 1, onCreate: _onCreate);
  }
  Future _onCreate(Database db, int version) async {
    await db.execute('''
          CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            number TEXT NOT NULL,
            comment TEXT NOT NULL,
            type INTEGER,
            status INTEGER,
            image1 TEXT,
            image2 TEXT,
            image3 TEXT,
            latitude TEXT NOT NULL,
            longitude TEXT NOT NULL,
            date TEXT
          )
          ''');
    await db.execute('''
          CREATE TABLE IF NOT EXISTS send_posts (
            id INTEGER,
            name TEXT NOT NULL,
            number TEXT NOT NULL,
            comment TEXT NOT NULL,
            type INTEGER,
            status INTEGER,
            admin_comment TEXT,
            image1 TEXT,
            image2 TEXT,
            image3 TEXT,
            latitude TEXT NOT NULL,
            longitude TEXT NOT NULL,
            date TEXT
          )
          ''');
  }
  Future<int> insertSendPost(Map<String, dynamic> row) async {
    Database? db = await instance.database;
    return await db!.insert('send_posts', row);
  }
  Future<int> insertPost(Map<String, dynamic> row) async {
    Database? db = await instance.database;
    return await db!.insert('posts', row);
  }
  Future<List<Map<String, dynamic>>> getPosts() async {
    Database? db = await instance.database;
    return await db!.rawQuery(
        "SELECT * FROM posts ORDER BY date DESC");
  }
  Future<List<Map<String, dynamic>>> getSendPosts() async {
    Database? db = await instance.database;
    return await db!.rawQuery(
        "SELECT * FROM send_posts");
  }
  Future<int> deletePost(id) async {
    Database? db = await instance.database;
    return await db!.delete('posts',
        where: 'id = ?', whereArgs: [id]);
  }
  
  Future<int> updatePostStatus(Map<String, dynamic> row, int id) async {
    Database? db = await instance.database;
    return await db!.update('send_posts', row,
        where: 'id = ?',
        whereArgs: [id]);
  }

  Future<int> getUnsendPostsCount() async {
    Database? db = await instance.database;
    var x = await db!.rawQuery('SELECT COUNT(*) FROM posts');
    int count = Sqflite.firstIntValue(x) ?? 0;
    return count;
  }
  
  Future<int> getSendPostsCount() async {
    Database? db = await instance.database;
    var x = await db!.rawQuery('SELECT COUNT(*) FROM send_posts');
    int count = Sqflite.firstIntValue(x) ?? 0;
    return count;
  }
}
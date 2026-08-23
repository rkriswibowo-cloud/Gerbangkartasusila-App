<?php
$baseDir = __DIR__ . '/mobile/lib';

// 1. MODELS
$hotelDart = <<<DART
class Hotel {
  final int id;
  final String name;
  final String slug;
  final String description;
  final String address;
  final double? latitude;
  final double? longitude;
  final String? thumbnail;
  final String? phone;

  Hotel({
    required this.id, required this.name, required this.slug, required this.description,
    required this.address, this.latitude, this.longitude, this.thumbnail, this.phone,
  });

  factory Hotel.fromJson(Map<String, dynamic> json) {
    return Hotel(
      id: json['id'],
      name: json['name'],
      slug: json['slug'],
      description: json['description'] ?? '',
      address: json['address'] ?? '',
      latitude: json['latitude'] != null ? double.parse(json['latitude'].toString()) : null,
      longitude: json['longitude'] != null ? double.parse(json['longitude'].toString()) : null,
      thumbnail: json['thumbnail'],
      phone: json['phone'],
    );
  }
}
DART;
file_put_contents("$baseDir/models/hotel.dart", $hotelDart);

$culinaryDart = <<<DART
class Culinary {
  final int id;
  final String name;
  final String slug;
  final String description;
  final String address;
  final double? latitude;
  final double? longitude;
  final String? thumbnail;

  Culinary({
    required this.id, required this.name, required this.slug, required this.description,
    required this.address, this.latitude, this.longitude, this.thumbnail,
  });

  factory Culinary.fromJson(Map<String, dynamic> json) {
    return Culinary(
      id: json['id'],
      name: json['name'],
      slug: json['slug'],
      description: json['description'] ?? '',
      address: json['address'] ?? '',
      latitude: json['latitude'] != null ? double.parse(json['latitude'].toString()) : null,
      longitude: json['longitude'] != null ? double.parse(json['longitude'].toString()) : null,
      thumbnail: json['thumbnail'],
    );
  }
}
DART;
file_put_contents("$baseDir/models/culinary.dart", $culinaryDart);

$articleDart = <<<DART
class Article {
  final int id;
  final String title;
  final String slug;
  final String content;
  final String? thumbnail;
  final String? publishedAt;

  Article({
    required this.id, required this.title, required this.slug, required this.content,
    this.thumbnail, this.publishedAt,
  });

  factory Article.fromJson(Map<String, dynamic> json) {
    return Article(
      id: json['id'],
      title: json['title'],
      slug: json['slug'],
      content: json['content'] ?? '',
      thumbnail: json['thumbnail'],
      publishedAt: json['published_at'],
    );
  }
}
DART;
file_put_contents("$baseDir/models/article.dart", $articleDart);

// 2. SCREENS
$screens = ['hotels_screen.dart' => 'Hotel', 'culinary_screen.dart' => 'Culinary', 'articles_screen.dart' => 'Article'];

foreach ($screens as $file => $entity) {
    $plural = strtolower($entity) . 's';
    if ($entity == 'Culinary') $plural = 'culinary';
    $title = $entity == 'Culinary' ? 'Culinary Spots' : $entity . 's';
    $nameField = $entity == 'Article' ? 'title' : 'name';
    $descField = $entity == 'Article' ? 'content' : 'description';
    $imgField = 'thumbnail';
    
    $screenDart = <<<DART
import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/${strtolower($entity)}.dart';

class {$entity}sScreen extends StatefulWidget {
  const {$entity}sScreen({super.key});

  @override
  State<{$entity}sScreen> createState() => _{$entity}sScreenState();
}

class _{$entity}sScreenState extends State<{$entity}sScreen> {
  final ApiService _apiService = ApiService();
  List<$entity> _items = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchData();
  }

  Future<void> _fetchData() async {
    try {
      final data = await _apiService.get{$entity}s();
      setState(() {
        _items = data.map((json) => $entity.fromJson(json)).toList();
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('$title', style: TextStyle(color: Colors.black87, fontWeight: FontWeight.bold)),
        backgroundColor: Colors.white,
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.black87),
      ),
      body: _isLoading 
        ? const Center(child: CircularProgressIndicator())
        : ListView.builder(
            padding: const EdgeInsets.all(16),
            itemCount: _items.length,
            itemBuilder: (context, index) {
              final item = _items[index];
              return Card(
                margin: const EdgeInsets.only(bottom: 16),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                clipBehavior: Clip.antiAlias,
                elevation: 2,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Image.network(
                      _apiService.getImageUrl(item.$imgField),
                      height: 200, width: double.infinity, fit: BoxFit.cover,
                      errorBuilder: (c,e,s) => Container(height: 200, color: Colors.grey[200], child: const Icon(Icons.image, size: 50)),
                    ),
                    Padding(
                      padding: const EdgeInsets.all(16),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(item.$nameField, style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                          const SizedBox(height: 8),
                          Text(item.$descField, maxLines: 2, overflow: TextOverflow.ellipsis, style: const TextStyle(color: Colors.black54, height: 1.5)),
                        ],
                      ),
                    )
                  ],
                ),
              );
            },
        ),
    );
  }
}
DART;
    file_put_contents("$baseDir/screens/$file", $screenDart);
}

// Update ApiService
$apiServiceContent = file_get_contents("$baseDir/services/api_service.dart");
$apiMethods = <<<DART
  Future<List<dynamic>> getHotels() async {
    try {
      final response = await _dio.get('/hotels');
      final data = response.data['data'];
      return (data is List) ? data : (data['data'] ?? []);
    } catch (e) {
      return [];
    }
  }

  Future<List<dynamic>> getCulinary() async {
    try {
      final response = await _dio.get('/culinary');
      final data = response.data['data'];
      return (data is List) ? data : (data['data'] ?? []);
    } catch (e) {
      return [];
    }
  }

  Future<List<dynamic>> getArticles() async {
    try {
      final response = await _dio.get('/articles');
      final data = response.data['data'];
      return (data is List) ? data : (data['data'] ?? []);
    } catch (e) {
      return [];
    }
  }
}
DART;
$apiServiceContent = str_replace("}\n", "\n$apiMethods", $apiServiceContent);
file_put_contents("$baseDir/services/api_service.dart", $apiServiceContent);

// Add Drawer to main_layout.dart
$mainLayoutContent = file_get_contents("$baseDir/screens/main_layout.dart");
$drawerDart = <<<DART
  Widget _buildDrawer(BuildContext context) {
    return Drawer(
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          DrawerHeader(
            decoration: BoxDecoration(
              color: Colors.green[700],
            ),
            child: const Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                CircleAvatar(
                  radius: 30,
                  backgroundColor: Colors.white,
                  child: Icon(Icons.person, size: 40, color: Colors.grey),
                ),
                SizedBox(height: 16),
                Text('Kediri Explorer', style: TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold)),
              ],
            ),
          ),
          ListTile(
            leading: const Icon(Icons.hotel, color: Colors.blue),
            title: const Text('Hotels & Accommodations'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(context, MaterialPageRoute(builder: (_) => const HotelsScreen()));
            },
          ),
          ListTile(
            leading: const Icon(Icons.restaurant, color: Colors.orange),
            title: const Text('Culinary Spots'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(context, MaterialPageRoute(builder: (_) => const CulinarysScreen()));
            },
          ),
          ListTile(
            leading: const Icon(Icons.article, color: Colors.purple),
            title: const Text('Articles & News'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(context, MaterialPageRoute(builder: (_) => const ArticlesScreen()));
            },
          ),
          const Divider(),
          ListTile(
            leading: const Icon(Icons.logout, color: Colors.red),
            title: const Text('Logout'),
            onTap: () {
               // Logout logic
            },
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
DART;
$mainLayoutContent = str_replace("  @override\n  Widget build(BuildContext context) {", $drawerDart, $mainLayoutContent);
$mainLayoutContent = str_replace("return Scaffold(", "return Scaffold(\n      drawer: _buildDrawer(context),", $mainLayoutContent);

// Add imports
$imports = <<<DART
import 'hotels_screen.dart';
import 'culinary_screen.dart';
import 'articles_screen.dart';
DART;
$mainLayoutContent = str_replace("import 'profile_screen.dart';", "import 'profile_screen.dart';\n$imports", $mainLayoutContent);

file_put_contents("$baseDir/screens/main_layout.dart", $mainLayoutContent);

echo "Done.\n";

<?php
$baseDir = __DIR__ . '/mobile/lib/screens';

// 1. GENERATE DETAIL SCREENS
$hotelDetail = <<<DART
import 'package:flutter/material.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:latlong2/latlong.dart';
import '../models/hotel.dart';
import '../services/api_service.dart';

class HotelDetailScreen extends StatelessWidget {
  final Hotel hotel;
  HotelDetailScreen({super.key, required this.hotel});
  final ApiService _apiService = ApiService();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(title: Text(hotel.name), backgroundColor: Colors.white, elevation: 0, foregroundColor: Colors.black),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Image.network(
              _apiService.getImageUrl(hotel.thumbnail),
              width: double.infinity, height: 250, fit: BoxFit.cover,
              errorBuilder: (c,e,s) => Container(height: 250, color: Colors.grey[200], child: const Icon(Icons.hotel, size: 80)),
            ),
            Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(hotel.name, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  if (hotel.phone != null) ...[
                    Row(children: [const Icon(Icons.phone, color: Colors.blue), const SizedBox(width: 8), Text(hotel.phone!, style: const TextStyle(fontSize: 16))]),
                    const SizedBox(height: 16),
                  ],
                  const Text('Description', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text(hotel.description, style: const TextStyle(fontSize: 16, height: 1.5)),
                  const SizedBox(height: 16),
                  const Text('Location', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text(hotel.address, style: const TextStyle(fontSize: 16)),
                  const SizedBox(height: 16),
                  if (hotel.latitude != null && hotel.longitude != null)
                    Container(
                      height: 200, width: double.infinity,
                      decoration: BoxDecoration(borderRadius: BorderRadius.circular(16)),
                      clipBehavior: Clip.antiAlias,
                      child: FlutterMap(
                        options: MapOptions(initialCenter: LatLng(hotel.latitude!, hotel.longitude!), initialZoom: 15),
                        children: [
                          TileLayer(urlTemplate: 'https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', userAgentPackageName: 'com.etourism.kediri'),
                          MarkerLayer(markers: [Marker(point: LatLng(hotel.latitude!, hotel.longitude!), width: 80, height: 80, child: const Icon(Icons.location_pin, color: Colors.red, size: 40))]),
                        ],
                      ),
                    ),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }
}
DART;
file_put_contents("$baseDir/hotel_detail_screen.dart", $hotelDetail);

$culinaryDetail = <<<DART
import 'package:flutter/material.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:latlong2/latlong.dart';
import '../models/culinary.dart';
import '../services/api_service.dart';

class CulinaryDetailScreen extends StatelessWidget {
  final Culinary culinary;
  CulinaryDetailScreen({super.key, required this.culinary});
  final ApiService _apiService = ApiService();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(title: Text(culinary.name), backgroundColor: Colors.white, elevation: 0, foregroundColor: Colors.black),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Image.network(
              _apiService.getImageUrl(culinary.thumbnail),
              width: double.infinity, height: 250, fit: BoxFit.cover,
              errorBuilder: (c,e,s) => Container(height: 250, color: Colors.grey[200], child: const Icon(Icons.restaurant, size: 80)),
            ),
            Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(culinary.name, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  const Text('Description', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text(culinary.description, style: const TextStyle(fontSize: 16, height: 1.5)),
                  const SizedBox(height: 16),
                  const Text('Location', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text(culinary.address, style: const TextStyle(fontSize: 16)),
                  const SizedBox(height: 16),
                  if (culinary.latitude != null && culinary.longitude != null)
                    Container(
                      height: 200, width: double.infinity,
                      decoration: BoxDecoration(borderRadius: BorderRadius.circular(16)),
                      clipBehavior: Clip.antiAlias,
                      child: FlutterMap(
                        options: MapOptions(initialCenter: LatLng(culinary.latitude!, culinary.longitude!), initialZoom: 15),
                        children: [
                          TileLayer(urlTemplate: 'https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', userAgentPackageName: 'com.etourism.kediri'),
                          MarkerLayer(markers: [Marker(point: LatLng(culinary.latitude!, culinary.longitude!), width: 80, height: 80, child: const Icon(Icons.location_pin, color: Colors.red, size: 40))]),
                        ],
                      ),
                    ),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }
}
DART;
file_put_contents("$baseDir/culinary_detail_screen.dart", $culinaryDetail);

$articleDetail = <<<DART
import 'package:flutter/material.dart';
import '../models/article.dart';
import '../services/api_service.dart';

class ArticleDetailScreen extends StatelessWidget {
  final Article article;
  ArticleDetailScreen({super.key, required this.article});
  final ApiService _apiService = ApiService();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(title: const Text('Article'), backgroundColor: Colors.white, elevation: 0, foregroundColor: Colors.black),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Image.network(
              _apiService.getImageUrl(article.thumbnail),
              width: double.infinity, height: 250, fit: BoxFit.cover,
              errorBuilder: (c,e,s) => Container(height: 250, color: Colors.grey[200], child: const Icon(Icons.article, size: 80)),
            ),
            Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(article.title, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  if (article.publishedAt != null)
                     Text('Published: \${article.publishedAt}', style: const TextStyle(color: Colors.grey, fontSize: 14)),
                  const SizedBox(height: 24),
                  Text(article.content, style: const TextStyle(fontSize: 16, height: 1.6)),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }
}
DART;
file_put_contents("$baseDir/article_detail_screen.dart", $articleDetail);


// 2. UPDATE LIST SCREENS
function updateScreen($file, $importName, $screenName, $modelVar) {
    $content = file_get_contents($file);
    $content = str_replace("import '../services/api_service.dart';", "import '../services/api_service.dart';\nimport '$importName';", $content);
    
    $inkwellStart = <<<DART
              return InkWell(
                onTap: () {
                  Navigator.push(context, MaterialPageRoute(builder: (_) => $screenName($modelVar: item)));
                },
                child: Card(
DART;
    $content = str_replace("return Card(", $inkwellStart, $content);
    $content = str_replace("                ),\n              );", "                ),\n              ),\n              );", $content);
    file_put_contents($file, $content);
}

updateScreen("$baseDir/hotels_screen.dart", 'hotel_detail_screen.dart', 'HotelDetailScreen', 'hotel');
updateScreen("$baseDir/culinary_screen.dart", 'culinary_detail_screen.dart', 'CulinaryDetailScreen', 'culinary');
updateScreen("$baseDir/articles_screen.dart", 'article_detail_screen.dart', 'ArticleDetailScreen', 'article');

echo "Done.\n";
